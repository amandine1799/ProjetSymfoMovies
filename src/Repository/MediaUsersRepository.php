<?php

namespace App\Repository;

use App\Entity\MediaUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MediaUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaUsers[]    findAll()
 * @method MediaUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaUsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MediaUsers::class);
    }

    // /**
    //  * @return MediaUsers[] Returns an array of MediaUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaUsers
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
