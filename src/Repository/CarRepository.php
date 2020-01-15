<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    
     public function searchCar($criteria)
     {
         return $this->createQueryBuilder('c')
            ->leftJoin('c.cities', 'city')
            ->andWhere('c.name = :cityName')
            ->setParameter('cityName', $criteria['city']->getName())
            ->andWhere('c.color = :color')
            ->setParameter('colo', $criteria['colo'])
            ->andWhere('c.carburant = :carburant')
            ->setParameter('carburant', $criteria['carburant'])
            ->andWhere('c.price > :minimumPrice' )
            ->setParameter('mimimumPrice', $criteria['minimumPrice'])
            ->andWhere('c.price < :maximumPrice' )
            ->setParameter('maximumPrice', $criteria['maximumPrice'])
             ->getQuery()
            ->getResult()
        ;
     }
    

    /*
    public function findOneBySomeField($value): ?Car
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
