<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Use Faker/php
        $faker = \Faker\Factory::create('fr_FR');

        // Generate 10 fake wishes
        for ($i=0; $i < 15; $i++) { 
            $category = new Category();
            $category->setName($faker->unique()->words(3, true));
            $manager->persist($category);
            $this->addReference('category'.$i, $category);
        }

        $manager->flush();
    }
}
