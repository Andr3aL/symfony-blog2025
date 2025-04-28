<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticlesCrudController extends AbstractCrudController
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Articles::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void // est appelé juste avant l'insertion de n'importe quel objet
    {
        if (!$entityInstance instanceof Articles) return; // on vérifie que l'objet qu'on est en train d'insérer est bien un article. Si ce n'est pas le cas, on return
        $user = $this->security->getUser(); // on récupère l'utilisateur actuellement connecté grâce au service Security de symfony
        // getUser() : permet de récupérer l'utilisateur actuellement connecté (grâce à la session à l'interieur)
        $entityInstance->setUser($user); // on dit à l'article que l'utilisateur qui l'a créé est l'utilisateur actuellement connecté
        parent::persistEntity($entityManager, $entityInstance); // on appelle la méthode persistEntity de la classe parente (AbstractCrudController) pour persister l'article dans la base de données
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('title'),
            TextareaField::new('content'),
            // setBasePath indique comment Easy admin va trouver l'image uploadee par l'utilisateur
            //le serveur ou Easy admin va stocker l'image uploadee par l'utilisateur : setUploadDir
            // indique comment Easy admin va nommer le nom de l'image uploadee par l'utilisateur : setUploadedFileNamePattern
            ImageField::new('image')->setBasePath('images/')->setUploadDir('public/images/')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            AssociationField::new('category')->setFormTypeOption('choice_label', 'name')
            ->setLabel('Catégories')
            ->formatValue(function ($value, $entity) {
                // Récupère les noms des catégories
                return implode(', ', $entity->getCategory()->map(function($category) {
                    return $category->getName();
                })->toArray());
            }),
            AssociationField::new('user', 'Users')->hideOnForm()
        ];
    }

    public function configureActions(Actions $actions): Actions
    
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
            
    }
    
}
