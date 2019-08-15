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
        // Valeur de base
        $tmp = 0;
        $req = 'SELECT m FROM App\Entity\Media m ';
        
        // Si le genre est défini
        // -------
        if($genre != null){
            // Requete pour trier les médias par genre
            $req .= 'WHERE m.genres = :genre ';
            $tmp = 1;

            // Si le type est défini (en + du genre)
            if($type != null){
                // Requete pour trier les médias par type (en + du genre)
                $req .= 'AND m.type = :type ';
                $tmp = 2;
                // Si la décenie est définie (en + du genre et du type)
                if($decenie != null){
                    // Ex. on a 1980 minimum, on mets l'année maximum dans la décenie (1989)
                    $decenieMax = (string)intval($decenie)+9;
                    // Requete pour trier les médias entre la décenie minimum et maximum (en + du genre et du type=)
                    $req .= 'AND m.released_year BETWEEN :decenie AND :decenieMax ';
                    $tmp = 3;
                }
            }
            // Si la décénie est définie (en + du genre et sans le type)
            else if($decenie != null){
                $decenieMax = (string)intval($decenie)+9;
                $req .= 'AND m.released_year BETWEEN :decenie AND :decenieMax ';
                $tmp = 7;
            }
        }
        // Si le genre n'est pas défini, et si le type est défini
        // --------
        else if($type != null){
            // Requete pour trier les médias par type
            $req .= 'WHERE m.type = :type ';
            $tmp = 4;
            // Si la décenie est défini (en + du type)
            if($decenie != null){
                $decenieMax = (string)intval($decenie)+9;
                $req .= 'AND m.released_year BETWEEN :decenie AND :decenieMax ';
                $tmp = 5;
            }
        } 
        // Si la décenie est définie
        // -------
        else if($decenie != null){
            $decenieMax = (string)intval($decenie)+9;
            $req .= 'WHERE m.released_year BETWEEN :decenie AND :decenieMax ';
            $tmp = 6;
        } 
        // Si rien n'est défini
        else {
            $req .= 'ORDER BY m.id DESC';
        }
        
        // Execute ta requete
        $sql = $this->getEntityManager()->createQuery(
            $req
        );
        
        switch ($tmp){
            case 1:
                $param = ['genre' => $genre];
                break;
            case 2:
                $param = ['genre' => $genre,'type' => $type];
                break;
            case 7:
                $param = [
                    'genre' => $genre,
                    'decenie' => $decenie,
                    'decenieMax' => $decenieMax
                ];
                break;
            case 4:
                $param = ['type' => $type];
                break;
            case 5:
                $param = [
                    'type' => $type,
                    'decenie' => $decenie,
                    'decenieMax' => $decenieMax
                ];
                break;
            case 6:
                $param = [
                    'decenie' => $decenie,
                    'decenieMax' => $decenieMax
                ];
                break;
            case 3:
                $param = [
                    'genre' => $genre,
                    'decenie' => $decenie,
                    'decenieMax' => $decenieMax,
                    'type' => $type
                ];
                break;
            default:
                $param = [];
        }

        // Mets les parametres pour ta requete
        $sql->setParameters($param);
        return $sql->getResult();
    }

    public function findAll()
    {
        $sql = $this->createQueryBuilder('m')
                    ->orderBy('m.id', 'DESC')
                    ->getQuery();

        return $sql->execute();
    }
}
