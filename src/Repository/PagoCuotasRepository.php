<?php

namespace App\Repository;

use App\Entity\PagoCuotas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PagoCuotas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagoCuotas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagoCuotas[]    findAll()
 * @method PagoCuotas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoCuotasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PagoCuotas::class);
    }

    public function findByContrato($value)
    {
        return $this->createQueryBuilder('p')
            ->join('p.cuota','c')
            ->andWhere('c.contrato = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByPago($pago)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('sum(p.monto) as total')
            ->andWhere('p.pago = :val')
            ->setParameter('val', $pago)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return PagoCuotas[] Returns an array of PagoCuotas objects
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
    public function findOneBySomeField($value): ?PagoCuotas
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
