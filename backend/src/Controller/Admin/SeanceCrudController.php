<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class SeanceCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Seance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->security->getUser();
        $isEdit = $pageName === 'edit';
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        $isCoach = in_array('ROLE_COACH', $user->getRoles());

        return [
            DateTimeField::new('date_heure', 'Date et heure')
                ->setFormat('dd/MM/yyyy HH:mm'),

            ChoiceField::new('type_seance', 'Type de séance')
                ->setChoices([
                    'Solo' => 'solo',
                    'Duo' => 'duo',
                    'Trio' => 'trio',
                ]),

            TextField::new('theme_seance', 'Thème de la séance'),

            ChoiceField::new('niveau_seance', 'Niveau')
                ->setChoices([
                    'Débutant' => 'débutant',
                    'Intermédiaire' => 'intermédiaire',
                    'Avancé' => 'avancé',
                ]),

            ChoiceField::new('statut', 'Statut')
                ->setChoices([
                    'Prévue' => 'prévue',
                    'Validée' => 'validée',
                    'Annulée' => 'annulée',
                ]),

            AssociationField::new('coach_id', 'Coach')
                ->setDisabled(!$isAdmin)
                ->setRequired(true)
                ->setFormTypeOption('data', $isEdit ? null : $user)
        ];
    }



    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Seance) {
            return;
        }

        $user = $this->security->getUser();
        if ($user) {
            $entityInstance->setCoachId($user);
        }

        $existingSeance = $entityManager->getRepository(Seance::class)->findOneBy([
            'coach_id' => $entityInstance->getCoachId(),
            'date_heure' => $entityInstance->getDateHeure(),
        ]);

        if ($existingSeance) {
            $this->addFlash('danger', "Ce coach a déjà une séance prévue à cet horaire !");
            return;
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->security->getUser();

        if ($user->getRoles() === ['ROLE_COACH'] && $entityInstance->getCoachId() !== $user) {
            throw new \Exception("Vous ne pouvez pas modifier une séance qui ne vous appartient pas.");
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
