<?php

namespace App\Repository;

use App\Entity\Request;
use App\Entity\Veterinary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Request>
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function findAllWaiting(Veterinary|null $vet = null): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NULL');

        if ($vet) {
            $query->andWhere('r.veterinary = :vet')->setParameter('vet', $vet);
        }

        return $query->getQuery()->getResult();
    }

    public function findAllScheduled(Veterinary|null $vet = null): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NOT NULL')
            ->andWhere('r.date_fulfilled IS NULL');

        if ($vet) {
            $query->andWhere('r.veterinary = :vet')->setParameter('vet', $vet);
        }

        return $query->getQuery()->getResult();
    }

    public function findAllDone(Veterinary|null $vet = null): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NOT NULL')
            ->andWhere('r.date_fulfilled IS NOT NULL');

        if ($vet) {
            $query->andWhere('r.veterinary = :vet')->setParameter('vet', $vet);
        }

        return $query->getQuery()->getResult();
    }

    public function save(Request $request): int
    {
        $em = $this->getEntityManager();
        $em->persist($request);
        $em->flush();

        return $request->getId();
    }
}
