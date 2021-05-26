<?php

namespace App\Service;
use App\Entity\Contrato;
use App\Entity\Cuota;
use App\Entity\Usuario;
use App\Entity\Configuracion;
use App\Entity\AgendaObservacion;
use App\Entity\AgendaStatus;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class ContratoFunciones
{
    private $container;
    private $container2;
    private $generator;

    public function __construct(Security $security,UrlGeneratorInterface $generatorUrl,ContainerInterface $container){
        $this->container=$security;   
        $this->generator=$generatorUrl;
        $this->container2=$container;

        
    }

    public function terminarContrato($contrato,$status,$observacion)
    {
        $u = $this->container->getUser();
        $em=$this->container2->get('doctrine');
        $entityManager = $this->container2->get('doctrine')->getManager();

        $agendaStatus=$em->getRepository(AgendaStatus::class)->find($status);
        $observacion_texto= $observacion;
        //$entityManager = $this->getDoctrine()->getManager();
        $agenda=$contrato->getAgenda();
        $agenda->setStatus($agendaStatus);

        $contrato->setFechaDesiste(new \DateTime(date("Y-m-d H:i:s") ));
        $entityManager->persist($agenda);
        $entityManager->flush();
        $entityManager->persist($contrato);
        $entityManager->flush();

        $observacion=new AgendaObservacion();
        $observacion->setAgenda($agenda);
        $observacion->setUsuarioRegistro($em->getRepository(Usuario::class)->find($u->getId()));
        $observacion->setStatus($agendaStatus);
        $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
        $observacion->setObservacion($observacion_texto);
        $entityManager->persist($observacion);
        $entityManager->flush();
        if($status==12){
            $error_toast="Toast.fire({
                icon: 'success',
                title: 'Cliente desconoce contrato'
              })";
        }else{

            $dateInicio=strtotime($contrato->getFechaCreacion()->format('Y-m-d'));
            $dateFin=strtotime(date('Y-m-d'));

            $interval = $dateFin-$dateInicio;


            if(($interval/60/60/24)>10){
                //$_cuota=$cuotaRepository->findOneBy(['contrato'=>$contrato],['numero'=>'desc']);
                $_cuota=$em->getRepository(Cuota::class)->findOneBy(['contrato'=>$contrato],['numero'=>'desc']);
                $configuracion=$em->getRepository(Configuracion::class)->find(1);

                $numeroCuota=$_cuota->getNumero();
                $numeroCuota++;
                $cuota=new Cuota();

                $cuota->setContrato($contrato);
                $cuota->setNumero($numeroCuota);
                $cuota->setFechaPago(new \DateTime(date('Y-m-d H:i')));
                $cuota->setMonto($configuracion->getValorMulta());
                $cuota->setIsMulta(true);
                $entityManager->persist($cuota);
                $entityManager->flush();
            }
            $error_toast="Toast.fire({
                icon: 'success',
                title: 'Cliente desiste de contrato'
              })";
        }
        return $error_toast;
    }



}