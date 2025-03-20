<?php

namespace App\DataFixtures;

use App\Entity\Coach;
use App\Entity\Exercice;
use App\Entity\Seance;
use App\Entity\Sportif;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $coachs = [];
        for ($i = 0; $i < 5; $i++) {
            $coach = new Coach();
            $coach->setEmail($faker->email);
            $coach->setPrenom($faker->firstName);
            $coach->setNom($faker->lastName);
            $coach->setRoles(['ROLE_COACH']);
            $coach->setTarifHoraire($faker->randomFloat(2, 20, 100));
            $coach->setSpecialites($faker->words);

            $hashedPassword = $this->passwordHasher->hashPassword($coach, 'coach');
            $coach->setPassword($hashedPassword);

            $manager->persist($coach);
            $coachs[] = $coach;
        }

        $sportifs = [];
        for ($i = 0; $i < 10; $i++) {
            $sportif = new Sportif();
            $sportif->setEmail($faker->email);
            $sportif->setPrenom($faker->firstName);
            $sportif->setNom($faker->lastName);
            $sportif->setRoles(['ROLE_SPORTIF']);
            $sportif->setNiveauSportif($faker->randomElement(['Débutant', 'Intermédiaire', 'Avancé']));
            $sportif->setDateInscription($faker->dateTimeBetween('-1 year', 'now'));

            $hashedPassword = $this->passwordHasher->hashPassword($sportif, 'sportif');
            $sportif->setPassword($hashedPassword);

            $manager->persist($sportif);
            $sportifs[] = $sportif;
        }

        $admin = new Utilisateur();
        $admin->setEmail("admin@admin.fr");
        $admin->setPrenom($faker->firstName);
        $admin->setNom($faker->lastName);
        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        $coach = new Coach();
        $coach->setEmail("coach@coach.fr");
        $coach->setPrenom($faker->firstName);
        $coach->setNom($faker->lastName);
        $coach->setRoles(['ROLE_COACH']);
        $coach->setTarifHoraire($faker->randomFloat(2, 20, 100));
        $coach->setSpecialites($faker->words);

        $hashedPassword = $this->passwordHasher->hashPassword($coach, 'coach');
        $coach->setPassword($hashedPassword);

        $manager->persist($coach);

        $exercices = [];
        for ($i = 0; $i < 15; $i++) {
            $exercice = new Exercice();
            $exercice->setNom($faker->word);
            $exercice->setDescription($faker->sentence);
            $exercice->setDureeEstimee($faker->numberBetween(5, 60));
            $exercice->setDifficulte($faker->randomElement(['Facile', 'Moyen', 'Difficile']));

            $manager->persist($exercice);
            $exercices[] = $exercice;
        }

        for ($i = 0; $i < 10; $i++) {
            $seance = new Seance();
            $seance->setDateHeure($faker->dateTimeBetween('now', '+3 months'));
            $seance->setTypeSeance($faker->randomElement(['Musculation', 'Cardio', 'Yoga']));
            $seance->setThemeSeance($faker->word);
            $seance->setNiveauSeance($faker->randomElement(['Débutant', 'Intermédiaire', 'Avancé']));
            $seance->setStatut($faker->randomElement(['Confirmée', 'Annulée', 'Reportée']));

            $seance->setCoachId($faker->randomElement($coachs));

            for ($j = 0; $j < rand(1, 3); $j++) {
                $seance->addSportif($faker->randomElement($sportifs));
            }

            for ($j = 0; $j < rand(2, 6); $j++) {
                $seance->addExercice($faker->randomElement($exercices));
            }
            $manager->persist($seance);
        }

        $manager->flush();
    }
}
