<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $abby = (new Animal())
            ->setName('Abby')
            ->setSpecies('dog')
            ->setBreed('idk')
            ->setGender('female')
            ->setColor('brown')
            ->setHeight(33)
            ->setWeight(8)
            ->setImage('/pics/dog.jpg')
            ->setDiscoveryDate(new \DateTime('2024-8-28'))
            ->setDiscoveryPlace('Brno')
            ->setCastration(false);
        $manager->persist($abby);
        $this->addReference('abby', $abby);

        $pickle = (new Animal())
            ->setName('Pickle')
            ->setSpecies('cat')
            ->setBreed('idk')
            ->setGender('male')
            ->setColor('ginger')
            ->setHeight(26)
            ->setWeight(4)
            ->setImage('/pics/cat.jpg')
            ->setDiscoveryDate(new \DateTime('2024-6-16'))
            ->setDiscoveryPlace('Bystřice nad Pernštejnem')
            ->setCastration(true);
        $manager->persist($pickle);
        $this->addReference('pickle', $pickle);

        $melman = (new Animal())
            ->setName('Melman')
            ->setSpecies('giraffe')
            ->setBreed('idk')
            ->setGender('male')
            ->setColor('yellow')
            ->setHeight(470)
            ->setWeight(1132)
            ->setImage('/pics/giraffe.jpg')
            ->setDiscoveryDate(new \DateTime('2023-01-28'))
            ->setDiscoveryPlace('Woods')
            ->setCastration(false);
        $manager->persist($melman);
        $this->addReference('melman', $melman);

        $manager->flush();
    }
}
