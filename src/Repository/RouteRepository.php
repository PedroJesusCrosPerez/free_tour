<?php

namespace App\Repository;

use App\Entity\Locality;
use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Route>
 *
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    
    /**
     * Devuelve el nombre de la localidad para una ruta específica.
     *
     * @param int $routeId El ID de la ruta.
     * @return string|null El nombre de la localidad o null si no se encuentra.
     */
    public function getLocality($route_id): ?string
    {
        return $this->createQueryBuilder('r')
            ->select('l.name')
            ->join('r.route_item', 'ri')
            ->join('ri.item', 'i')
            ->join('i.locality', 'l')
            ->andWhere('r.id = :route_id')
            ->setParameter('route_id', $route_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getLocality2($route_id)
    {
        return $this->createQueryBuilder('ri')
            ->join('route_items','ri', 'WITH', 'r.id = ri.route_id')
            ->join('i', 'WITH', 'ri.item_id = i.id')
            ->join('l', 'WITH', 'i.locality_id = l.id')
            ->where('r.id = :route_id')
            ->setParameter('route_id', $route_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ->select('l.id')
            ;
    }

//    /**
//     * @return Route[] Returns an array of Route objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Route
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
