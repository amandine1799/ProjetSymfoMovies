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

    public function findWithFilters($genre, $type, $decade)
    {
        $q = $this->createQueryBuilder('m')
                  ->orderBy('m.released', 'DESC');

        if ($type !== null) {
            $q->andWhere('m.type = :type');
            $q->setParameter('type', $type);
        }

        if ($genre !== null) {
            $q->andWhere('m.genres = :genre');
            $q->setParameter('genre', $genre);
        }

        if ($decade !== null) {
            $q->andWhere('YEAR(m.released) BETWEEN :decade AND (:decade + 9)');
            $q->setParameter('decade', $decade);
        }

        return $q->getQuery()->execute();
    }

    public function findAll(string $order_field = 'released', bool $reverse = false)
    {
        $sql = $this->createQueryBuilder('m')
                    ->orderBy('m.' . $order_field, $reverse ? 'DESC' : 'ASC')
                    ->getQuery();

        return $sql->execute();
    }

    public function getDistinctDecades()
    {
        $sql = $this->getEntityManager()->createQuery(
            'SELECT DISTINCT m.released FROM App\Entity\Media m'
        );
        $res = $sql->getResult();

        $decades = [];
        foreach($res as $date) {
            $y = (int)$date['released']->format('Y');
            $y = floor($y / 10) * 10;
            $decades[] = (int)$y;
        }
        
        sort($decades);
        $decades = array_unique($decades);
        return $decades;
    }

    public function aleatoireMedias()
    {
        $sql = $this->getEntityManager()->createQuery(
            "SELECT m FROM App\Entity\Media m ORDER BY RAND()"
        );
        $sql->setMaxResults(1);
        return $sql->getSingleResult();
    }

    public function lastReleased()
        {
            $sql = $this->getEntityManager()->createQuery(
                "SELECT m FROM App\Entity\Media m WHERE m.released > CURRENT_DATE()"
            );

            return $sql->getResult();
        }

}




