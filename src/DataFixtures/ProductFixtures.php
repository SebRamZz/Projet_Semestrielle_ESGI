<?php

namespace App\DataFixtures;

use App\Entity\DrivingSchool;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $drivingSchools = $manager->getRepository(DrivingSchool::class)->findAll();
        $numberDrivingSchools = count($drivingSchools);

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();

            $product->setProductName($faker->name());
            $product->setProductDescription($faker->paragraph());
            $product->setProductHour($faker->randomNumber(2));
            $product->setProductPrice($faker->randomNumber(4));
            $product->setDrivingSchool($drivingSchools[$faker->numberBetween(0, $numberDrivingSchools - 1)]);

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DrivingSchoolFixtures::class,
        ];
    }
}