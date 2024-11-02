<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllSearch(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.name LIKE :query')
            ->where('u.surname LIKE :query')
            ->where('CONCAT(u.name, \' \', u.surname) LIKE :query')
            ->where('CONCAT(u.surname, \' \', u.name) LIKE :query')
            ->orWhere('u.email LIKE :queryEmail')
            ->setParameter('query', "%$query%")
            ->setParameter('queryEmail', "$query%")
            ->getQuery()
            ->getResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function save(User $user): int
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user->getId();
    }

    public function delete(User $user)
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
