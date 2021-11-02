<?php

namespace App\Repository;

use App\Entity\ContratoMee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContratoMee|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContratoMee|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContratoMee[]    findAll()
 * @method ContratoMee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratoMeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContratoMee::class);
    }

    // /**
    //  * @return ContratoMee[] Returns an array of ContratoMee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContratoMee
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
