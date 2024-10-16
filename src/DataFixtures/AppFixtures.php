<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $animal = (new Animal())
            ->setName('Abby')
            ->setSpecies('dog')
            ->setBreed('idk')
            ->setGender('female')
            ->setColor('brown')
            ->setHeight(33)
            ->setWeight(8)
            ->setImage('/pics/dog.jpg');
        $manager->persist($animal);

        $animal = (new Animal())
            ->setName('Pickle')
            ->setSpecies('cat')
            ->setBreed('idk')
            ->setGender('male')
            ->setColor('ginger')
            ->setHeight(26)
            ->setWeight(4)
            ->setImage('/pics/cat.jpg');
        $manager->persist($animal);

        $animal = (new Animal())
            ->setName('Melman')
            ->setSpecies('giraffe')
            ->setBreed('idk')
            ->setGender('male')
            ->setColor('yellow')
            ->setHeight(470)
            ->setWeight(1132)
            ->setImage('/pics/giraffe.jpg');
        $manager->persist($animal);

        $manager->flush();
    }
}
