<?php

namespace App\Controller\Admin;

use App\Command\Helpers\Utility\GeneralUtility;
use App\Entity\Contracts;
use App\EventListener\CalculateBudgetListener;
use App\Form\AssignedRolesType;
use App\Form\ProjectsType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ContractsCrudController extends AbstractCrudController
{
    #[Route('/contracts', name: 'contracts')]
    public static function getEntityFqcn(): string
    {
        return Contracts::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
        GeneralUtility::calculateBudget($entityInstance, $entityManager);
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        GeneralUtility::calculateBudget($entityInstance, $entityManager);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('contractID')
            ->add((ChoiceFilter::new('status', 'Status'))
                ->setChoices([
                    'Draft' => 'Draft',
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                    'Executed' => 'Executed',
                    'Finished' => 'Finished',
                ])
                ->renderExpanded()
                ->canSelectMultiple());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->setEntityLabelInSingular('Ledger')
            ->setEntityLabelInPlural('Ledger');
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewInvoice = Action::new('downloadWord', 'SOW')
            ->linkToCrudAction('renderSow');
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, $viewInvoice)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'SOW Name');
        yield NumberField::new('contractID', 'SOW #')->hideOnIndex();
        yield NumberField::new('fte', 'FTE')->setSortable(true);
        yield ChoiceField::new('status')
            ->setChoices([
                'Draft' => 'Draft',
                'Active' => 'Active',
                'Inactive' => 'Inactive',
                'Executed' => 'Executed',
                'Finished' => 'Finished',
            ]);
        yield MoneyField::new('budgetCap', 'Budget')
            ->setStoredAsCents(false)
            ->setCurrency('USD');
        yield MoneyField::new('advancedPayment', 'Adv')
            ->setStoredAsCents(false)
            ->setCurrency('USD');
        yield CollectionField::new('relatedProjects', 'Related Projects')
            ->setEntryType(ProjectsType::class)
            ->setFormTypeOptions([
                'by_reference' => true,
            ])
            ->allowAdd();
        yield DateTimeField::new('startDate')
            ->setRequired(true)
            ->setSortable(true);
        yield DateTimeField::new('endDate')
            ->setRequired(true)
            ->setSortable(true);
        yield CollectionField::new('assignedRoles', 'Assigned Roles')
            ->setEntryType(AssignedRolesType::class)
            ->setFormTypeOptions([
                'by_reference' => false
            ])
            ->setRequired(true)
            ->allowAdd()
            ->allowDelete();
        if (Crud::PAGE_DETAIL === $pageName) {
            yield Field::new('budgetByMonth')
                ->setCustomOption('monthBudgets',
                    GeneralUtility::getMonthlyBudgets($this->getContext()->getEntity()->getInstance()))
                ->setLabel('Budget by months')
                ->setTemplatePath('admin/budget.html.twig')
                ->onlyOnDetail();
        }
    }

    public function renderSow()
    {
        return GeneralUtility::renderFile($this->getContext()->getEntity()->getInstance());
    }

}
