<?php

namespace App\Repository;

use App\Entity\Actors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Actors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actors[]    findAll()
 * @method Actors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActorsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Actors::class);
    }

    // /**
    //  * @return Actors[] Returns an array of Actors objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Actors
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
