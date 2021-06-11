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
    public function asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago,$esMulta=false){

        $entityManager = $this->_em;
        $contrato->setIsFinalizado(false);

        do{
            $pagostatus=false;
            $cuota=$cuotaRepository->findOneByPrimeraVigente($contrato->getId(),$esMulta);
            $pagoCuotas=$pagoCuotasRepository->findByPago($pago->getId());

            if(null == $pagoCuotas["total"]){
                $total=0;
            }else{
                $total=$pagoCuotas["total"];
            }
            if($cuota){
                //cuando el pago es menor o igual a la deuda.
                if(($pago->getMonto()-$total)<=($cuota->getMonto()-$cuota->getPagado())){
                    
                    $cuota->setPagado($cuota->getPagado()+($pago->getMonto()-$total));

                    $entityManager->persist($cuota);
                    $entityManager->flush();

                    $pagoCuota=new PagoCuotas();
                    $pagoCuota->setCuota($cuota);
                    $pagoCuota->setPago($pago);
                    $pagoCuota->setMonto($pago->getMonto()-$total);
                    $entityManager->persist($pagoCuota);
                    $entityManager->flush();
                }else{
                    
                    
                    if(($pago->getMonto()-$total)>=($cuota->getMonto()-$cuota->getPagado())){
                        
                        
                        $pagoCuota=new PagoCuotas();
                        $pagoCuota->setCuota($cuota);
                        $pagoCuota->setPago($pago);
                        $pagoCuota->setMonto($cuota->getMonto()-$cuota->getPagado());
                        $entityManager->persist($pagoCuota);
                        $entityManager->flush();

                        $cuota->setPagado($cuota->getMonto());
                        $entityManager->persist($cuota);
                        $entityManager->flush();
                        $pagostatus=true;
                    }else if(($pago->getMonto()-$total)<($cuota->getMonto()-$cuota->getPagado())){
                        
                        
                        $pagoCuota=new PagoCuotas();
                        $pagoCuota->setCuota($cuota);
                        $pagoCuota->setPago($pago);
                        $pagoCuota->setMonto(($pago->getMonto()-$total));
                        $entityManager->persist($pagoCuota);
                        $entityManager->flush();

                        $cuota->setPagado(($pago->getMonto()-$total)+$cuota->getPagado());

                        $entityManager->persist($cuota);
                        $entityManager->flush();
                    }
                }

                //tomamos las cuotas y reseteamos el q_mov de las cobranzas
                $cobranzas = $contrato->getCobranzas();
                $qMov=0;
                foreach($cobranzas as $cobranza){
                    $qMov++;
                }
                if($contrato->getQMov()-$qMov>0){
                    $contrato->setQMov($contrato->getQMov()-$qMov);
                    $entityManager->persist($contrato);
                    $entityManager->flush();
                }
            }else{
                //si estan todas las cuotas pagadas, buscamos la ultima cuota pagada y agregamos el monto sobrante a esa cuota
                if($pago->getMonto()-$total>0){
                    $cuota=$cuotaRepository->findOneByUltimaPagada($contrato->getId());
                    if($cuota){

                        $pagoCuota=new PagoCuotas();
                        $pagoCuota->setCuota($cuota);
                        $pagoCuota->setPago($pago);
                        $pagoCuota->setMonto(($pago->getMonto()-$total));
                        $entityManager->persist($pagoCuota);
                        $entityManager->flush();

                        $cuota->setPagado(($pago->getMonto()-$total)+$cuota->getPagado());

                        $entityManager->persist($cuota);
                        $entityManager->flush();
                        //tomamos las cuotas y reseteamos el q_mov de las cobranzas
                        $cobranzas = $contrato->getCobranzas();
                        $qMov=0;
                        foreach($cobranzas as $cobranza){
                            $qMov++;
                        }
                        if($contrato->getQMov()-$qMov>0){
                            $contrato->setQMov($contrato->getQMov()-$qMov);
                            $entityManager->persist($contrato);
                            $entityManager->flush();
                        }
                    }
                }
            }

        }while($pagostatus);

        $cuota=$cuotaRepository->findOneByPrimeraVigente($contrato->getId(),$esMulta);

    
        $entityManager->persist($contrato);
        $entityManager->flush();
        return true;
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
