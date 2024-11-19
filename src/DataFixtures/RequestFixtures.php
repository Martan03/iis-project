<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Walk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $request = (new Request())
            ->setDateCreated(new \DateTime('2024-11-14 9:46'))
            ->setDateFulfilled(new \DateTime('2024-11-18 10:01'))
            ->setDescription(
                'Look at his neck, it so long. You should cure him...'
            )
            ->setExamination($this->getReference('exam'))
            ->setAnimal($this->getReference('melman'))
            ->setCaregiver($this->getReference('caregiver')->getCaregiver())
            ->setVeterinary(
                $this->getReference('veterinary')->getVeterinary()
            );
        $manager->persist($request);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
            UserFixtures::class,
            ExaminationFixtures::class,
        ];
    }
}
