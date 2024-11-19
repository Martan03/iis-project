<?php

namespace App\DataFixtures;

use App\Entity\Examination;
use App\Entity\Request;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExaminationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $exam = (new Examination())
            ->setDate(new \DateTime('2024-11-18 10:00'))
            ->setResult("It is supposed to be... It's a giraffe...")
            ->setAnimal($this->getReference('melman'))
            ->setVeterinary(
                $this->getReference('veterinary')->getVeterinary()
            );
        $manager->persist($exam);
        $this->setReference('exam', $exam);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
            UserFixtures::class,
        ];
    }
}
