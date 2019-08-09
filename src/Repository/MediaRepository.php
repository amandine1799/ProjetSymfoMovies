<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function findBySearch($genre, $type, $decenie)
    {
        $decenieMax = (string)intval($decenie)+10;
        $sql = $this->getEntityManager()->createQuery(
            '
                SELECT m FROM App\Entity\Media m
                WHERE m.genres = :genre
                AND m.type = :type
                AND m.released_year BETWEEN :decenie AND :decenieMax
            '
        )
        ->setParameters([
            'genre' => $genre,
            'type' => $type,
            'decenie' => $decenie,
            'decenieMax' => $decenieMax
        ]);
        return $sql->getResult();
    } 

    public function findAll()
    {
        $sql = $this->createQueryBuilder('m')
        ->orderBy('m.id', 'DESC')
        ->getQuery();

        return $sql->execute();
    }

    // /**
    //  * @return Media[] Returns an array of Media objects
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
    public function findOneBySomeField($value): ?Media
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
