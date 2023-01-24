<?php

namespace App\Controller\Admin;

use App\Entity\Equip;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;


class EquipCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equip::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('cicle'),
            TextField::new('curs'),
            NumberField::new('nota'),
            ImageField::new('imatge')
                ->setBasePath('/assets/img/') // carpeta img
                ->setUploadDir('/public/assets/img/')
                ->setRequired(false),

        ];
    }
    
}
