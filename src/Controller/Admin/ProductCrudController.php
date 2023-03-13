<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Shop\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setSearchFields(['category', 'name', 'description'])
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('category', 'Catégorie')
            ->setCrudController(CategoryCrudController::class)
            ->setFormTypeOption('choice_label', function ($category) {
                return $category->getName();
            }),
            TextField::new('name', 'Nom')
                ->setRequired(true),
            TextareaField::new('description', 'Description')
                ->setRequired(false),
            MoneyField::new('price')
                    ->setLabel('Prix')
                    ->setCurrency('EUR'),
            ImageField::new('image', 'Image Principale')
                ->setBasePath('/images/products')
                ->setUploadDir('public/images/products')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setLabel('Image'),
            CollectionField::new('imagesProducts', 'Autres Images')
                ->useEntryCrudForm()
                ->setHelp('Vous pouvez ajouter des images supplémentaires pour votre produit'),
            CollectionField::new('options', 'Options')
                ->useEntryCrudForm(),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->setLabel('Slug')
                ->setHelp('Le slug est le nom qui apparaîtra dans la barre de navigation, 
                il est généré automatiquement à partir du nom du produit,')
                ->hideOnIndex(),
            DateTimeField::new('releaseAt', 'Date de sortie')
                ->setFormat('short')
                ->setFormTypeOptions([
                    'data' => new DateTimeImmutable('now'),
                ])
                ->setTimezone('Europe/Paris'),
        ];
    }
}
