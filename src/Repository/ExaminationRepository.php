<?php

namespace App\Repository;

use App\Entity\Examination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Examination>
 */
class ExaminationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Examination::class);
    }

    public function save(Examination $exam): int
    {
        $em = $this->getEntityManager();
        $em->persist($exam);
        $em->flush();

        return $exam->getId();
    }

    public function delete(Examination $exam)
    {
        $em = $this->getEntityManager();
        $em->remove($exam);
        $em->flush();
    }
}
