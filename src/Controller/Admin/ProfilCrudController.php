<?php

namespace App\Controller\Admin;

use App\Entity\Profil;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProfilCrudController extends AbstractCrudController
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Profil::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void // est appelé juste avant l'insertion de n'importe quel objet
    {
        if (!$entityInstance instanceof Profil) return; // on vérifie que l'objet qu'on est en train d'insérer est bien un article. Si ce n'est pas le cas, on return
        $user = $this->security->getUser(); // on récupère l'utilisateur actuellement connecté grâce au service Security de symfony
        // getUser() : permet de récupérer l'utilisateur actuellement connecté (grâce à la session à l'interieur)
        $entityInstance->setUser($user); // on dit à l'article que l'utilisateur qui l'a créé est l'utilisateur actuellement connecté
        parent::persistEntity($entityManager, $entityInstance); // on appelle la méthode persistEntity de la classe parente (AbstractCrudController) pour persister l'article dans la base de données
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextareaField::new('description'),
            ImageField::new('picture')->setBasePath('images/')->setUploadDir('public/images/')->setUploadedFileNamePattern('[randomhash].[extension]'),
            DateField::new('datebirth')->setFormat('dd/MM/yyyy'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            AssociationField::new('user')->hideOnForm()
        ];
    }
    
}
