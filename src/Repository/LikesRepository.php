<?php

namespace App\Repository;

use App\Entity\Likes;
use App\Entity\Media;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    /**
     * @return Likes[] Returns an array of Likes objects
     */
    public function findByUserMedia(Users $users, Media $media)
    { 
        return $this->createQueryBuilder('l')
            ->andWhere('l.users = :users')
            ->andWhere('l.media = :media')
            ->setParameters([
                'users' => $users,
                'media' => $media
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Likes
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
