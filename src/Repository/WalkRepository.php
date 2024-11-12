<?php

namespace App\Repository;

use App\Entity\Walk;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Walk>
 */
class WalkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Walk::class);
    }

    public function findAllFilter(int $id, DateTime $start, DateTime $end)
    {
        return $this->createQueryBuilder('w')
            ->where('w.animal = :id')
            ->andWhere(
                'w.start BETWEEN :start and :end OR ' .
                'w.end BETWEEN :start and :end'
            )
            ->setParameter('id', $id)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function getFuture(): array {
        $now = time();
        return $this->createQueryBuilder('w')
            ->where('w.start > :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function save(Walk $walk): int
    {
        $em = $this->getEntityManager();
        $em->persist($walk);
        $em->flush();

        return $walk->getId();
    }
}
