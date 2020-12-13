<?php

namespace App\Repository;

use App\Entity\Agenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agenda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agenda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agenda[]    findAll()
 * @method Agenda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agenda::class);
    }
    /**
      * @return Agenda[] Returns an array of Agenda objects
    */
    public function findByPers($usuario=null,$empresa=null,$compania=null,$status=null, $filtro=null,$esAbogado=null,$otros=null)
    {
        $query=$this->createQueryBuilder('a');
        if(!is_null($status)){
            $query->andWhere('a.status in ('.$status.')');
        }
        if(!is_null($empresa)){
            $query->join('a.cuenta','c');
            $query->andWhere('c.empresa = '.$empresa);
        }
        if($esAbogado==1){
        
            if(!is_null($usuario)){
                $query->andWhere('a.abogado = '.$usuario);
            }else{
                $query->andWhere('a.abogado is not null ');
            }

        }else{
            if(!is_null($usuario)){
                $query->andWhere('a.agendador = '.$usuario);
            }
        }
        if(!is_null($compania)){
            $query->andWhere('a.cuenta = '.$compania);
        }
        if(!is_null($filtro)){ 
            $query->andWhere("(a.nombreCliente like '%$filtro%' or a.telefonoCliente like '%$filtro%' or a.emailCliente like '%$filtro%')")
         ;

        }

        if(!is_null($otros)){ 
            $query->andWhere($otros)
         ;

        }
        return $query->getQuery()
            ->getResult()
        ;
    }

    public function findByPersGroup($usuario=null,$empresa=null,$compania=null,$status=null, $filtro=null,$esAbogado=null, $otros=null)
    {
        $query=$this->createQueryBuilder('a');
        $query->select(array('a','s','count(s.id) as valor'));
        $query->join('a.status','s');
        if(!is_null($status)){
            $query->andWhere('a.status in ('.$status.')');
        }
        if(!is_null($empresa)){
            $query->join('a.cuenta','c');
            $query->andWhere('c.empresa = '.$empresa);
        }
        if($esAbogado==1){
        
            if(!is_null($usuario)){
                $query->andWhere('a.abogado = '.$usuario);
            }else{
                $query->andWhere('a.abogado is not null ');
            }

        }else{
            if(!is_null($usuario)){
                $query->andWhere('a.agendador = '.$usuario);
            }
        }
        if(!is_null($compania)){
            $query->andWhere('a.cuenta = '.$compania);
        }
        if(!is_null($filtro)){ 
            $query->andWhere("(a.nombreCliente like '%$filtro%' or a.telefonoCliente like '%$filtro%' or a.emailCliente like '%$filtro%')")
         ;

        }
        if(!is_null($otros)){ 
            $query->andWhere($otros)
         ;

        }
        $query->addGroupBy('s.id');

        return $query->getQuery()
            ->getResult()
        ;

    }
    public function findByAgendGroup($usuario=null,$empresa=null,$compania=null,$status=null, $filtro=null,$esAbogado=null, $otros=null)
    {


        $query=$this->createQueryBuilder('a');
        $query->select(array('a','u','count(u.id) as valor'));
        if($esAbogado==1){
            $query->join('a.abogado','u');
        }else{
            $query->join('a.agendador','u');
        }
        
        if(!is_null($status)){
            $query->andWhere('a.status in ('.$status.')');
        }
        if(!is_null($empresa)){
            $query->join('a.cuenta','c');
            $query->andWhere('c.empresa = '.$empresa);
        }
        if($esAbogado==1){
        
            if(!is_null($usuario)){
                $query->andWhere('a.abogado = '.$usuario);
            }else{
                $query->andWhere('a.abogado is not null ');
            }

        }else{
      
            if(!is_null($usuario)){
                $query->andWhere('a.agendador = '.$usuario);
            }
        }
        if(!is_null($compania)){
            $query->andWhere('a.cuenta = '.$compania);
        }
        if(!is_null($filtro)){ 
            $query->andWhere("(a.nombreCliente like '%$filtro%' or a.telefonoCliente like '%$filtro%' or a.emailCliente like '%$filtro%')")
         ;

        }
        if(!is_null($otros)){ 
            $query->andWhere($otros)
         ;

        }
        $query->addGroupBy('u.id');

        return $query->getQuery()
            ->getResult()
        ;

    }
    // /**
    //  * @return Agenda[] Returns an array of Agenda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Agenda
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
