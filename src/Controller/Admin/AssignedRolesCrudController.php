<?php

namespace App\Controller\Admin;

use App\Entity\AssignedRoles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AssignedRolesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AssignedRoles::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
