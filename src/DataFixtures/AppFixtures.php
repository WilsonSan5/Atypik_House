<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Habitats;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   $faker = Factory::create('fr_FR');

        // Création des habitats

        for ($i=0 ; $i < 20 ; $i++){
            $faker = Factory::create('fr_FR');
            $habitat = new Habitats;

            $habitat->setAdresse( $faker->address());
            $habitat->setTitre($faker->word());
            $habitat->setImage($faker->imageUrl(640, 480, 'house', true));
            $habitat->setCategorie(null);
            $manager->persist($habitat);
        };

        // Création des 4 categories

        $categorie = new Categories;
        $categorie->setTitre('Forêt');
        $categorie->setDescription($faker->text());
        $manager->persist($categorie);

        $categorie = new Categories;
        $categorie->setTitre('Plage');
        $categorie->setDescription($faker->text());
        $manager->persist($categorie);

        $categorie = new Categories;
        $categorie->setTitre('Montagne');
        $categorie->setDescription($faker->text());
        $manager->persist($categorie);

        $categorie = new Categories;
        $categorie->setTitre('Campagne');
        $categorie->setDescription($faker->text());
        $manager->persist($categorie);

        $manager->flush();
    }
}
