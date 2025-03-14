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

class AppFixtures extends Fixture
{
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

            $coach->setPassword("coach");

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

            $sportif->setPassword("sportif");

            $manager->persist($sportif);
            $sportifs[] = $sportif;
        }

        $admin = new Utilisateur();
        $admin->setEmail("admin@admin.fr");
        $admin->setPrenom("");
        $admin->setNom($faker->lastName);
        $admin->setRoles(['ROLE_ADMIN']);

        $admin->setPassword("admin");

        $manager->persist($admin);

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

            for ($j = 0; $j < rand(2, 5); $j++) {
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
