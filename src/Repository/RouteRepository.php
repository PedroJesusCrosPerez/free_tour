<?php

namespace App\Repository;

use App\Entity\Locality;
use App\Entity\Ratings;
use App\Entity\Reservation;
use App\Entity\Route;
use App\Entity\Tour;
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
     * Devuelve un array de rutas específicas según fechas.
     *
     * @param date $date_start Fecha de inicio de la ruta.
     * @param date $date_end Fecha de inicio de la ruta.
     * @return Route[]|null Devuelve un vector de objetos Route o null si no se encuentan.
     */
    public function findByDateRange(\DateTime $datetime_start, \DateTime $datetime_end): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.datetime_start >= :datetime_start')
            ->setParameter('datetime_start', $datetime_start->format('Y-m-d 00:00:00'))
            ->andWhere('r.datetime_end <= :datetime_end')
            ->setParameter('datetime_end', $datetime_end->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Devuelve un array de rutas específicas según fechas.
     *
     * @param date $date_start Fecha de inicio de la ruta.
     * @param date $date_end Fecha de inicio de la ruta.
     * @return Route[]|null Devuelve un vector de objetos Route o null si no se encuentan.
     */
    public function getRatings(int $route_id): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.id as route_id', 'res.id as reservation_id', 'rat.route_rating', 'rat.guide_rating')
            ->join(Tour::class, 't', 'WITH', 'r = t.route')
            ->join(Reservation::class, 'res', 'WITH', 't = res.tour')
            ->join(Ratings::class, 'rat', 'WITH', 'res = rat.reservation')
            ->andWhere('r.id = :route_id')
            ->setParameter('route_id', $route_id)
            ->getQuery()
            ->getResult();
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
