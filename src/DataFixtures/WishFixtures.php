<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Use Faker/php
        $faker = \Faker\Factory::create('fr_FR');

        // Generate 10 fake wishes
        for ($i=0; $i < 15; $i++) { 
            $wish = new Wish();
            $wish->setTitle('Wish '.$faker->word)
                ->setAuthor($faker->lastName() .' '. $faker->firstName())
                ->setDescription($faker->sentences(3, true))
                ->setPublished($faker->numberBetween(0, 1))
                ->setDateCreated($faker->dateTimeBetween('-1 years', 'now'))
                ->setCategory($this->getReference('category'.rand(0,14), Category::class));
            $manager->persist($wish);
        }

        $manager->flush();
    }
}
