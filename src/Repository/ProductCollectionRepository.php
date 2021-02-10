<?php

namespace App\Repository;

use App\Entity\ProductCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCollection[]    findAll()
 * @method ProductCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCollection::class);
    }

    // /**
    //  * @return ProductCollection[] Returns an array of ProductCollection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductCollection
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
