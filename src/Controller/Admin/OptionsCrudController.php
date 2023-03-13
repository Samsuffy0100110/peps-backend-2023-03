<?php

namespace App\Controller\Admin;

use App\Entity\Shop\Options;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OptionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Options::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')
                ->setRequired(true),
            IntegerField::new('quantity', 'Quantité')
                ->setRequired(true),
            MoneyField::new('priceCustom', 'Prix')
                ->setCurrency('EUR')
                ->setRequired(true),
        ];
    }
}
