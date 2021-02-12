<?php

namespace App\Repository;

use App\Entity\Cuota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cuota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuota[]    findAll()
 * @method Cuota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuotaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuota::class);
    }
    public function findVencimiento(){
        $query=$this->createQueryBuilder('c');
        $query->join('c.contrato','co');
        $query->andWhere('c.monto>c.pagado or c.pagado is null');
        $query->groupBy('c.contrato');

        return $query->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Cuota[] Returns an array of Cuota objects
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
    public function findOneBySomeField($value): ?Cuota
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
