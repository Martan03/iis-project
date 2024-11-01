<?php

namespace App\Repository;

use App\Entity\Request;
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

    public function findAllWaiting(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findAllScheduled(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NOT NULL')
            ->andWhere('r.date_fulfilled IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findAllDone(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.examination IS NOT NULL')
            ->andWhere('r.date_fulfilled IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function save(Request $request): int
    {
        $em = $this->getEntityManager();
        $em->persist($request);
        $em->flush();

        return $request->getId();
    }
}
