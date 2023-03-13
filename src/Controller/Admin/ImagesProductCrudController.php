<?php

namespace App\Controller\Admin;

use App\Entity\Shop\ImagesProduct;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImagesProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImagesProduct::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('name', 'Image')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setLabel('Image'),
        ];
    }
}
