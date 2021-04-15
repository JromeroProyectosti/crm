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
    public function findVencimiento($usuario=null,$empresa=null,$compania=null,$filtro=null,$tipoUsuario=null,$vigente=true, $otros=null){
        $query=$this->createQueryBuilder('c');
        $query->join('c.contrato','co');
        $query->join('co.agenda','a');
        $query->join('a.cuenta','cu');
        if($vigente){
            $query->andWhere('c.monto>c.pagado or c.pagado is null');
        }else{
            $query->andWhere(' co.isFinalizado=true');
        }
        

        if(!is_null($empresa)){
            
            $query->andWhere('cu.empresa = '.$empresa);
        }
        switch($tipoUsuario){
            case 6://Abogado
                if(!is_null($usuario)){
                    $query->andWhere('a.abogado = '.$usuario)
                    ->andWhere("DATEDIFF(now(),c.fechaPago)<=30")
                    ->andWhere("c.numero=1");

                }
                break;
            case 7://Tramitador
                if(!is_null($usuario))
                    $query->andWhere('co.tramitador = '.$usuario);
                break;

        }
        
        
        if(!is_null($filtro)){ 
            $query->andWhere("(co.nombre like '%$filtro%' or co.rut like '%$filtro%')")
         ;

        }
        if(!is_null($compania)){
            $query->andWhere('a.cuenta = '.$compania);
        }
        
        if(!is_null($otros) && $otros!=''){ 
            $query->andWhere($otros)
         ;

        }

        $query->groupBy('c.contrato');

        return $query->getQuery()
            ->getResult()
        ;
    }

    public function findOneByPrimeraVigente($contrato,$isMulta=false): ?Cuota
    {
        $query=$this->createQueryBuilder('c')
        ->andWhere('c.contrato=:contra')
        ->setParameter('contra', $contrato)
        ->andWhere('c.monto>c.pagado or c.pagado is null')
        ;
       if($isMulta){
            $query->andWhere('c.isMulta = true');
        }
        $query
        ->setMaxResults(1);
        return $query->getQuery()
            
            ->getOneOrNullResult()
            
        ;
    }
    public function findOneByUltimaPagada($contrato): ?Cuota
    {
        $query=$this->createQueryBuilder('c')
        ->andWhere('c.contrato=:contra')
        ->setParameter('contra', $contrato)
        ->orderBy('c.numero', 'Desc')
        ->setMaxResults(1);
        return $query->getQuery()
            ->getOneOrNullResult()
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
