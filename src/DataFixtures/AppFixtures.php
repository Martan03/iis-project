<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Animal;
use App\Entity\Caregiver;
use App\Entity\Veterinary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

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
            ->setImage('/pics/dog.jpg')
            ->setDiscoveryDate(new \DateTime('2024-8-28'))
            ->setDiscoveryPlace('Brno')
            ->setCastration(false);

        $manager->persist($animal);

        $animal = (new Animal())
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
        $manager->persist($animal);

        $animal = (new Animal())
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
        $manager->persist($animal);

        $admin = (new Administrator())
            ->setEmail('admin@iis.com')
            ->setName('Admin')
            ->setSurname('Admin')
            ->setPhone('123456789');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $vet = (new Veterinary())
            ->setEmail('veterinary@iis.com')
            ->setName('Veterinary')
            ->setSurname('Veterinary')
            ->setPhone('123456789');
        $vet->setPassword($this->hasher->hashPassword($vet, ' '));
        $manager->persist($vet);

        $caregiver = (new Caregiver())
            ->setEmail('caregiver@iis.com')
            ->setName('Caregiver')
            ->setSurname('Caregiver')
            ->setPhone('123456789');
        $caregiver->setPassword(
            $this->hasher->hashPassword($caregiver, 'caregiver')
        );
        $manager->persist($caregiver);

        $manager->flush();
    }
}
