<?php

namespace App\Repository;

use App\Entity\Cobranza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cobranza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cobranza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cobranza[]    findAll()
 * @method Cobranza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CobranzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cobranza::class);
    }
    public function findByContrato($value)
    {
        $query=$this->createQueryBuilder('c')
        ->join('c.cuota','cu')
        ->andWhere('cu.contrato = :val');
        

        $query->setParameter('val', $value)
        ->orderBy('c.id', 'ASC');

        return $query
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Cobranza[] Returns an array of Cobranza objects
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
    public function findOneBySomeField($value): ?Cobranza
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
