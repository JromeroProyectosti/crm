<?php

namespace App\Repository;

use App\Entity\Pago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pago|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pago|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pago[]    findAll()
 * @method Pago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pago::class);
    }

    public function findByContrato($value)
    {
        return $this->createQueryBuilder('p')
            ->join('p.pagoCuotas','pc')
            ->join('pc.cuota','c')
            ->andWhere('c.contrato = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findUPByContrato($value)
    {
        return $this->createQueryBuilder('p')
            ->join('p.pagoCuotas','pc')
            ->join('pc.cuota','c')
            ->andWhere('c.contrato = :val')
            ->setParameter('val', $value)
            ->orderBy('p.fechaPago', 'Desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()

        ;
    }
    // /**
    //  * @return Pago[] Returns an array of Pago objects
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
    public function findOneBySomeField($value): ?Pago
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
