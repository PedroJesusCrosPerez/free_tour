<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }


    function findAllByClient_id($client_id) : array 
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.client = :client')
            ->setParameter('client', $client_id)
            ->andWhere('r.assistants IS NOT null') // ¿esta línea es correcta?
            ->orderBy('r.datetime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithRatingsByClient($client_id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.client = :client_id')
            ->andWhere('r.assistants IS NOT NULL')
            ->setParameter('client_id', $client_id)
            ->andWhere(
                $this->_em->getExpressionBuilder()->notIn(
                    'r.id',
                    $this->_em->createQueryBuilder()
                        ->select('IDENTITY(rat.reservation)')
                        ->from('App\Entity\Ratings', 'rat')
                        ->getDQL()
                )
            )
            ->getQuery()
            ->getResult()
        ;
    }
    
    



    
//    /**
//     * @return Reservation[] Returns an array of Reservation objects
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

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
