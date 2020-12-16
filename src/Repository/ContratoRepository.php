<?php

namespace App\Repository;

use App\Entity\Contrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrato[]    findAll()
 * @method Contrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrato::class);
    }
    public function findByPers($usuario=null,$empresa=null,$compania=null,$filtro=null,$agendador=null, $otros=null)
    {
        echo $usuario;

        $query=$this->createQueryBuilder('c');
        $query->join('c.agenda','a');
        $query->join('a.cuenta','cu');
        if(!is_null($empresa)){
            
            $query->andWhere('cu.empresa = '.$empresa);
        }
        if(!is_null($usuario)){
            $query->andWhere('a.abogado = '.$usuario);
        }
        if(!is_null($agendador)){
            
            $query->andWhere('a.agendador = '.$agendador);
        }
        if(!is_null($filtro)){ 
            $query->andWhere("(c.nombre like '%$filtro%')")
         ;

        }
        if(!is_null($compania)){
            $query->andWhere('a.cuenta = '.$compania);
        }
        if(!is_null($otros)){ 
            $query->andWhere($otros)
         ;

        }
        return $query
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Contrato[] Returns an array of Contrato objects
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
    public function findOneBySomeField($value): ?Contrato
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
