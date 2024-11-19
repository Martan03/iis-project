<?php

namespace App\DataFixtures;

use App\Entity\Walk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WalkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $date = new \DateTime();

        for ($i = 1; $i <= 14; $i++) {
            $start = (clone $date)->modify("+$i days");
            $end = clone $start;

            $hour = rand(8, 16);
            $start->setTime($hour, 00);
            $end->setTime($hour + 1, 30);

            $walk = (new Walk())
                ->setStart($start)
                ->setEnd($end)
                ->setAnimal($this->getReference('abby'));
            $manager->persist($walk);
        }

        for ($i = 1; $i <= 7; $i++) {
            $cnt = $i * 2;
            $start = (clone $date)->modify("+$cnt days");
            $end = clone $start;

            $hour = rand(8, 16);
            $start->setTime($hour, 00);
            $end->setTime($hour + 1, 00);

            $walk = (new Walk())
                ->setStart($start)
                ->setEnd($end)
                ->setAnimal($this->getReference('pickle'));
            $manager->persist($walk);
        }

        for ($i = 1; $i <= 3; $i++) {
            $cnt = $i * 4;
            $start = (clone $date)->modify("+$cnt days");
            $end = clone $start;

            $hour = rand(8, 16);
            $start->setTime($hour, 00);
            $end->setTime($hour, 30);

            $walk = (new Walk())
                ->setStart($start)
                ->setEnd($end)
                ->setAnimal($this->getReference('melman'));
            $manager->persist($walk);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class
        ];
    }
}
