<?php

namespace App\Form;

use App\Command\Scrapers\DataArtPM\Entities\Contract;
use App\Entity\Contracts;
use App\Entity\Projects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsType extends AbstractType
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
//            ->add('projectID')
//            ->add('clientName')
//            ->add('owner')
//            ->add('name', ChoiceType::class, [
//                'choices' => $this->getProjects(),
//                'multiple' => false,
//                'placeholder' => '',
//                'attr' => [
//                    'class' => 'new-project-input',
//                    'placeholder' => '',
//                ]
//            ])
            ->add('name')
//            ->add('projectGroup')
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'End Date',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'End Date',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('relatedContract', EntityType::class,[
                'class' => Contracts::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
            'related_contract' => null,
        ]);
    }

//    public function getProjects()
//    {
//        $projects = $this->em->getRepository(Projects::class)->findAll();
//
//        // создать массив опций выбора
//        $projectChoices = [];
//        foreach ($projects as $project) {
//            $projectChoices[$project->getName()] = $project->getId();
//        }
//
//        return $projectChoices;
//    }
}
