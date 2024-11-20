<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Caregiver;
use App\Entity\User;
use App\Entity\Veterinary;
use App\Entity\Volunteer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('admin@iis.com')
            ->setName('Ad')
            ->setSurname('Min')
            ->setPhone('123456789')
            ->setAdministrator(new Administrator());
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);
        $this->addReference('admin', $admin);

        $vet = (new User())
            ->setEmail('veterinary@iis.com')
            ->setName('Veter')
            ->setSurname('Inary')
            ->setPhone('123456789')
            ->setVeterinary(new Veterinary());
        $vet->setPassword($this->hasher->hashPassword($vet, 'veterinary'));
        $manager->persist($vet);
        $this->addReference('veterinary', $vet);

        $caregiver = (new User())
            ->setEmail('caregiver@iis.com')
            ->setName('Care')
            ->setSurname('Giver')
            ->setPhone('123456789')
            ->setCaregiver(new Caregiver());
        $caregiver->setPassword(
            $this->hasher->hashPassword($caregiver, 'caregiver')
        );
        $manager->persist($caregiver);
        $this->addReference('caregiver', $caregiver);

        $volunteer = (new User())
            ->setEmail('volunteer@iis.com')
            ->setName('Volun')
            ->setSurname('Teer')
            ->setPhone('412398567')
            ->setVolunteer((new Volunteer())->setVerified(true));
        $volunteer->setPassword(
            $this->hasher->hashPassword($volunteer, 'volunteer')
        );
        $manager->persist($volunteer);
        $this->addReference('volunteer', $volunteer);

        $super = (new User())
            ->setEmail('super@iis.com')
            ->setName('Super')
            ->setSurname('User')
            ->setPhone('314159265')
            ->setAdministrator(new Administrator())
            ->setVeterinary(new Veterinary())
            ->setCaregiver(new Caregiver())
            ->setVolunteer((new Volunteer())->setVerified(true));
        $super->setPassword($this->hasher->hashPassword($super, 'super'));
        $manager->persist($super);
        $this->addReference('super', $super);

        $manager->flush();
    }
}
