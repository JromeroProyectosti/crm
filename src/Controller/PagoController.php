<?php

namespace App\Controller;

use App\Entity\Pago;
use App\Entity\Usuario;
use App\Entity\Cuota;
use App\Entity\PagoCuotas;
use App\Entity\Contrato;
use App\Form\PagoType;
use App\Repository\ContratoRepository;
use App\Repository\ContratoRolRepository;
use App\Repository\PagoRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\CuotaRepository;
use App\Repository\CuentaRepository;
use App\Repository\PagoCuentasRepository;
use App\Repository\PagoCuotasRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * @Route("/pago")
 */
class PagoController extends AbstractController
{
    /**
     * @Route("/", name="pago_index", methods={"GET"})
     */
    public function index(ContratoRepository $contratoRepository, CuotaRepository $cuotaRepository,PagoRepository $pagoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $filtro=null;

        $compania=null;
        if(null !== $request->query->get('bFiltro') && $request->query->get('bFiltro')!=''){
            $filtro=$request->query->get('bFiltro');
        }
        if(null !== $request->query->get('bCompania') && $request->query->get('bCompania')!=0){
            $compania=$request->query->get('bCompania');
        }
        if(null !== $request->query->get('bFecha')){
            $aux_fecha=explode(" - ",$request->query->get('bFecha'));
            $dateInicio=$aux_fecha[0];
            $dateFin=$aux_fecha[1];
        }else{
            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');
        }
        $fecha="c.fechaCreacion between '$dateInicio' and '$dateFin 23:59:59'" ;
      
        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 4:
            case 1:
                //$query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento();
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
            break;
            
            default:
                //$query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento();
                $companias=$cuentaRepository->findByPers(null);
                
            break;
        }
        //$companias=$cuentaRepository->findByPers($user->getId());
        //$query=$contratoRepository->findAll();
        $cuotas=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));
        
        return $this->render('pago/index.html.twig', [
            'cuotas' => $cuotas,
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
        ]);
    }
    
    /**
     * @Route("/upload", name="pago_upload", methods={"GET","POST"})
     */
    public function upload(Request $request){
        $brochureFile = $_FILES['file']['name'][0];
            
        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($brochureFile) {


            $fichero_subido = $this->getParameter('url_root').
            $this->getParameter('img_pagos') . basename($_FILES['file']['name'][0]);
            
            if (move_uploaded_file($_FILES['file']['tmp_name'][0], $fichero_subido)) {
                echo "El fichero es válido y se subió con éxito.\n";
            } else {
                echo "¡Posible ataque de subida de ficheros!\n";
            }
            
            /*
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            // Move the file to the directory where brochures are stored
            echo $this->getParameter('url_root').
            $this->getParameter('pagos');
            $brochureFile->move($this->getParameter('url_root').
                $this->getParameter('pagos'),
                $newFilename
            );
            */
        }

        return $this->redirectToRoute('pago_index');
    }
    
    /**
     * @Route("/{id}", name="pago_show", methods={"GET"})
     */
    public function show(Pago $pago): Response
    {
        return $this->render('pago/show.html.twig', [
            'pago' => $pago,
        ]);
    }
    /**
     * @Route("/{id}/verpagos", name="verpagos_index", methods={"GET","POST"})
     */
    public function verpagos(Request $request, Contrato $contrato,PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $pagos=$pagoRepository->findByContrato($contrato);

        return $this->render('pago/verpagos.html.twig', [
            'pagos' => $pagos,
            'pagina'=>$pagina->getNombre()."/ Ingreso",
            'contrato'=>$contrato,
        ]);
    }

    /**
     * @Route("/{id}/verpagos_view", name="verpagos_view", methods={"GET","POST"})
     */
    public function verpagosShow(Request $request, Contrato $contrato,PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
    
        return $this->render('pago/verpagos_show.html.twig', [
            'pagina'=>$pagina->getNombre()."/ Detalle pagos",
            'contrato'=>$contrato,
        ]);

    }
    
    /**
     * @Route("/{id}/new", name="pago_new", methods={"GET","POST"})
     */
    public function new(Request $request,Contrato $contrato,CuotaRepository $cuotaRepository,PagoCuotasRepository $pagoCuotasRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $pago = new Pago();
        $pago->setFechaRegistro(new \DateTime(date('Y-m-d H:i:s')));
        $pago->setUsuarioRegistro($user);
        $form = $this->createForm(PagoType::class, $pago);
        $form->add('fechaRegistro',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->add('fechaPago',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pago);
            $entityManager->flush();
            
            do{
                $pagostatus=false;
                $cuota=$cuotaRepository->findOneByPrimeraVigente($contrato->getId());
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
                }else{
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
                        }
                    }
                }

            }while($pagostatus);
            
           
            return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
           
        }

        return $this->render('pago/new.html.twig', [
            'pago' => $pago,
            'contrato'=>$contrato,
            'pagina'=>$pagina->getNombre()."/ Ingreso Pagos",
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="pago_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pago $pago): Response
    {
        $form = $this->createForm(PagoType::class, $pago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pago_index');
        }

        return $this->render('pago/edit.html.twig', [
            'pago' => $pago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pago_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pago $pago): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pago);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pago_index');
    }
}
