<?php

namespace App\Repository;

use App\Entity\Registration;
use App\Entity\Volunteer;
use App\Entity\Walk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registration>
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function save(Registration $registration): int
    {
        $em = $this->getEntityManager();
        $em->persist($registration);
        $em->flush();

        return $registration->getId();
    }

    public function getSelected(Walk $walk): Registration|null {
        return $this->createQueryBuilder('r')
            ->where('r.walk = ' . $walk->getId())
            ->andWhere("r.state != 'waiting' AND r.state != 'rejected'")
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getHistory(Volunteer $volunteer): array {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.volunteer', 'v')
            ->innerJoin('r.walk', 'w')
            ->leftJoin('w.animal', 'a')
            ->addSelect('v', 'w', 'a')
            ->where('v.id = ' . $volunteer->getId())
            ->orderBy('w.start', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
