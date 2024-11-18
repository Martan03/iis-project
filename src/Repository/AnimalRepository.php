<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function findAllSearch(string $query): array
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.name LIKE :query')
            ->orWhere('a.species LIKE :querySpec')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('querySpec', $query . '%')
            ->getQuery()
            ->getResult();
    }

    public function countSpecies(): int {
        return $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a.species)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(Animal $animal): int
    {
        $em = $this->getEntityManager();
        $em->persist($animal);
        $em->flush();

        return $animal->getId();
    }

    public function delete(Animal $animal)
    {
        $em = $this->getEntityManager();
        $em->remove($animal);
        $em->flush();
    }
}
