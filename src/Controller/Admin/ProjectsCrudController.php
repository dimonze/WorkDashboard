<?php

namespace App\Controller\Admin;

use App\Entity\Contracts;
use App\Entity\Projects;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProjectsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projects::class;
    }


    public function configureFields(string $pageName): iterable
    {
//        yield IdField::new('id', 'ID');
        yield IdField::new('projectID', 'project ID')->hideOnForm();
        yield TextField::new('clientName', 'Client Name');
        yield TextField::new('owner', 'Owner');
        yield TextField::new('name', 'Name');
        yield TextField::new('projectGroup', 'Project Group');
        yield DateTimeField::new('startDate', 'Start Date');
        yield DateTimeField::new('endDate', 'End Date');
        yield AssociationField::new('relatedContract')
            ->setFormTypeOptions([
                'class' => Contracts::class,
                'choice_label' => 'name',
            ]);
    }

}
