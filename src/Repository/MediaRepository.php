<?php

namespace App\Repository;

use App\Entity\Media;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


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
        // Medias are by default indexed in decreasing.
        $q = $this->createQueryBuilder('m')
                  ->orderBy('m.released', 'DESC');

        // When you selected a type it take it in the data base.
        if ($type !== null) {
            $q->andWhere('m.type = :type');
            $q->setParameter('type', $type);
        }

        // When you selected a genre it take it in the data base.
        if ($genre !== null) {
            $q->andWhere('m.genres = :genre');
            $q->setParameter('genre', $genre);
        }

        // When you selected a decade it take it in the data base.
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
        // We use SELECT DISTINCT for delete duplicates decades for our form.
        $sql = $this->getEntityManager()->createQuery(
            'SELECT DISTINCT m.released FROM App\Entity\Media m'
        );
        $res = $sql->getResult();

        // We do a calculation for to have 10 to 10' numbers in our form.  
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

    public function aleatoireMediasbygenre(Media $media)
    {
        $sql = $this->getEntityManager()->createQuery(
            "SELECT m FROM App\Entity\Media m WHERE m.genres = :genres AND m != :media ORDER BY RAND()"
        )
        ->setMaxResults(5)
        ->setParameters([
            'genres' => $media->getGenres(),
            'media' => $media
        ])
        ->getResult();
        return $sql;
    }

    public function nextReleased()
    {
        $sql = $this->getEntityManager()
                     ->createQuery("SELECT m FROM App\Entity\Media m WHERE m.released > CURRENT_DATE() ORDER BY m.released ASC");
        return $sql->getResult();
    }

    public function lastReleased()
    {
        $sql = $this->getEntityManager()
                     ->createQuery("SELECT m FROM App\Entity\Media m WHERE m.released BETWEEN :start_date AND CURRENT_DATE() ORDER BY m.released DESC")
                     ->setParameter('start_date', new \DateTime('-3 month')
                    );
        return $sql->getResult();
    }
}