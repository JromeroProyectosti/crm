<?php

namespace App\Controller;

use App\Entity\Pago;
use App\Entity\Usuario;
use App\Entity\Cuota;
use App\Entity\PagoCuotas;
use App\Entity\Contrato;
use App\Entity\Importacion;
use App\Form\PagoType;
use App\Form\ImportacionType;
use App\Repository\ImportacionRepository;
use App\Repository\ContratoRepository;
use App\Repository\ContratoRolRepository;
use App\Repository\PagoRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\CuotaRepository;
use App\Repository\CuentaRepository;
use App\Repository\PagoCuentasRepository;
use App\Repository\PagoCanalRepository;
use App\Repository\PagoTipoRepository;
use App\Repository\CuentaCorrienteRepository;
use App\Repository\PagoCuotasRepository;
use App\Repository\VencimientoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ConfiguracionRepository;
use App\Repository\DiasPagoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Service\ContratoFunciones;

/**
 * @Route("/cobranza")
 */
class CobranzaController extends AbstractController
{
    /**
     * @Route("/", name="cobranza_index", methods={"GET"})
     */
    public function index(ContratoRepository $contratoRepository, 
                        CuotaRepository $cuotaRepository,
                        PagoRepository $pagoRepository,
                        PaginatorInterface $paginator,
                        ModuloPerRepository $moduloPerRepository,
                        Request $request,
                        CuentaRepository $cuentaRepository,
                        VencimientoRepository $vencimientoRepository): Response
    {
        $this->denyAccessUnlessGranted('view','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza',$user->getEmpresaActual());
        $vencimientoArray=$vencimientoRepository->findBy(['empresa'=>$user->getEmpresaActual()],['id'=>'ASC'],1);
        $filtro=null;
        $folio=null;
        $compania=null;
        $vencimiento=$vencimientoArray[0];
        $otros=' DATEDIFF(now(),c.fechaPago)>'.$vencimiento->getValMax();
        $fecha=null;
        $error='';
        $error_toast="";
        if(null !== $request->query->get('error_toast')){
            $error_toast=$request->query->get('error_toast');
        }
        if(null !== $request->query->get('bFolio') && $request->query->get('bFolio')!=''){
            $folio=$request->query->get('bFolio');
            $otros=$otros." and co.id= $folio";

            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');
            

        }else{
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
            //$fecha="c.fechaPago between '$dateInicio' and '$dateFin 23:59:59' ";
        }
        $fecha=$otros;
        switch($user->getUsuarioTipo()->getId()){
            case 1://tramitador
            case 3:
            case 4:
            case 8:
            
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,null,true,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 7://tramitador
                $query=$cuotaRepository->findVencimiento($user->getId(),null,null,$filtro,7,true,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 6: //abogado
                $query=$cuotaRepository->findVencimiento($user->getId(),null,null,$filtro,6,true,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 11://Administrativo

                //$query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,null,true,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
            break;
            case 12://Cobradores
               
                $fecha.=" and co.lote in (".implode(",",$user->getLotes()).") ";
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,null,true,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;

            default:
                //$query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,null,true,$fecha);
                $companias=$cuentaRepository->findByPers(null);
                
            break;
        }
        //$companias=$cuentaRepository->findByPers($user->getId());
        //$query=$contratoRepository->findAll();
        $cuotas=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'fechaPago', 'defaultSortDirection' => 'Asc'));
        
        return $this->render('cobranza/index.html.twig', [
            'cuotas' => $cuotas,
            'bFiltro'=>$filtro,
            'bFolio'=>$folio,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'finalizado'=>false,
            'error'=>$error,
            'error_toast'=>$error_toast,
        ]);
    }

    /**
     * @Route("/finalizado", name="cobranza_finalizado", methods={"GET"})
     */
    public function finalizado(ContratoRepository $contratoRepository, CuotaRepository $cuotaRepository,PagoRepository $pagoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $this->denyAccessUnlessGranted('view','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza_finalizado',$user->getEmpresaActual());
        $filtro=null;
        $folio=null;
        $compania=null;
        $otros='';
        $otros='';
        $fecha=null;
        if(null !== $request->query->get('bFolio') && $request->query->get('bFolio')!=''){
            $folio=$request->query->get('bFolio');
            $otros=" co.id= $folio";

            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');
            $fecha=$otros;
        }else{
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
            //$fecha="c.fechaPago between '$dateInicio' and '$dateFin 23:59:59' ";
        }
      
        switch($user->getUsuarioTipo()->getId()){
            case 1://tramitador
            case 3:
            case 4:
            case 8:
            case 12:
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,7,false,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 7://tramitador
                $query=$cuotaRepository->findVencimiento($user->getId(),null,null,$filtro,7,false,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 6: //abogado
                $query=$cuotaRepository->findVencimiento($user->getId(),null,null,$filtro,6,false,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 11://Administrativo
                //$query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento(null,null,null,$filtro,null,false,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());

                break;
            
            default:
                //$query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha,true);
                $query=$cuotaRepository->findVencimiento($user->getId(),null,null,$filtro,null,false,$fecha);
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
            'bFolio'=>$folio,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'finalizado'=>true,
        ]);
    }

    /**
     * @Route("/resumen", name="cobranza_resumen", methods={"GET"})
     */
    public function resumen(ContratoRepository $contratoRepository, CuotaRepository $cuotaRepository,PagoRepository $pagoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {

        $this->denyAccessUnlessGranted('view','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza_resumen',$user->getEmpresaActual());
        $filtro=null;
        $folio=null;
        $compania=null;
        $otros='';
        
        
        if(null !== $request->query->get('bCompania') && $request->query->get('bCompania')!=0){
            $compania=$request->query->get('bCompania');
        }
        if(null !== $request->query->get('bFecha')){
            $aux_fecha=explode(" - ",$request->query->get('bFecha'));
            $dateInicio=$aux_fecha[0];
            $dateFin=$aux_fecha[1];
        }else{
            $dateInicio=date('Y-m-d');
            $dateFin=date('Y-m-d');
        }
        $fecha="p.fechaRegistro between '$dateInicio' and '$dateFin 23:59:59'";
      
        switch($user->getUsuarioTipo()->getId()){
            case 1://tramitador
            case 3:
            case 4:
            case 8:
            case 12:
                $query=$pagoRepository->findByPers(null,null,null,$filtro,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());

                break;
            case 11://Administrativo
                //$query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha,true);
                $query=$pagoRepository->findByPers($user->getId(),null,null,$filtro,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
             break;
            
            default:
                //$query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha,true);
                $query=$pagoRepository->findByPers($user->getId(),null,null,$filtro,$fecha);
                $companias=$cuentaRepository->findByPers(null);
                
            break;
        }
        //$companias=$cuentaRepository->findByPers($user->getId());
        //$query=$contratoRepository->findAll();
        $pagos=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));
        
        return $this->render('pago/resumen.html.twig', [
            'pagos' => $pagos,
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
            
           /* if (move_uploaded_file($_FILES['file']['tmp_name'][0], $fichero_subido)) {
                echo "El fichero es válido y se subió con éxito.\n";
            } else {
                echo "¡Posible ataque de subida de ficheros!\n";
            }*/

            //echo filesize($_FILES['file']['tmp_name'][0]);
            $source=$_FILES['file']['tmp_name'][0];
            $imgInfo = getimagesize($source); 
            
            $mime = $imgInfo['mime']; 
             
            // Creamos una imagen
            switch($mime){ 
                case 'image/jpeg': 
                    $image = imagecreatefromjpeg($source); 
                    break; 
                case 'image/png': 
                    $image = imagecreatefrompng($source); 
                    break; 
                case 'image/gif': 
                    $image = imagecreatefromgif($source); 
                    break; 
                default: 
                    $image = imagecreatefromjpeg($source); 
            } 

            $quality=100;
            if(filesize($_FILES['file']['tmp_name'][0])>1000000){
                $quality=75;
            }
            if(filesize($_FILES['file']['tmp_name'][0])>2000000){
                $quality=60;
            }
            if(filesize($_FILES['file']['tmp_name'][0])>3000000){
                $quality=50;
            }
            if(filesize($_FILES['file']['tmp_name'][0])>4000000){
                $quality=40;
            }
            // Guardamos la imagen
            imagejpeg($image, $fichero_subido, $quality); 
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


        return $this->redirectToRoute('cobranza_index');
    }

    /**
     * @Route("/generalotes", name="cobranza_generalotes", methods={"GET","POST"})
     */
    public function generalotes(ContratoRepository $contratoRepository,ConfiguracionRepository $configuracionRepository){


        $contratos=$contratoRepository->findBy(['lote'=>null]);
        $configuracion=$configuracionRepository->find(1);
        $lote=1;
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach($contratos as $contrato){
            $contrato->setLote($lote);

            $entityManager->persist($contrato);
            $entityManager->flush();
            $lote++;
            if($lote>$configuracion->getLotes()){
                $lote=1;
            }
        }
        return $this->redirectToRoute('cobranza_index');
    }
    /**
     * @Route("/{id}", name="cobranza_show", methods={"GET"})
     */
    public function show(Pago $pago): Response
    {
        $this->denyAccessUnlessGranted('view','cobranza');
        $pagoCuotas=$pago->getPagoCuotas();
        foreach($pagoCuotas as $pagoCuota){
            $cuota=$pagoCuota->getCuota();
            $contrato=$cuota->getContrato();
        }
        return $this->render('pago/show.html.twig', [
            'pago' => $pago,
            'contrato'=>$contrato,
            'pagina'=>"Ver Pago",
        ]);
    }
    /**
     * @Route("/{id}/vercobranza", name="vercobranza_index", methods={"GET","POST"})
     */
    public function verpagos(Request $request, Contrato $contrato,PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza',$user->getEmpresaActual());
        $pagos=$pagoRepository->findByContrato($contrato);

        return $this->render('pago/verpagos.html.twig', [
            'pagos' => $pagos,
            'pagina'=>"Ingreso ". $pagina->getNombre(),
            'contrato'=>$contrato,
        ]);
    }

    /**
     * @Route("/{id}/vercobranza_view", name="vercobranza_view", methods={"GET","POST"})
     */
    public function verpagosShow(Request $request, Contrato $contrato,PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
    
        return $this->render('pago/verpagos_show.html.twig', [
            'pagina'=>"Detalle ".$pagina->getNombre(),
            'contrato'=>$contrato,
        ]);

    }
    /**
     * @Route("/{id}/detallepagos", name="detallepagos_index", methods={"GET","POST"})
     */
    public function detallepagos(Request $request, Cuota $cuota,PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $pagoCuotas=$cuota->getPagoCuotas();

        return $this->render('pago/detallepagos.html.twig', [
            'pagocuotas' => $pagoCuotas,
        ]);
        

    }
    /**
     * @Route("/{id}/new", name="cobranza_new", methods={"GET","POST"})
     */
    public function new(Request $request,Contrato $contrato,CuotaRepository $cuotaRepository,PagoCuotasRepository $pagoCuotasRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('create','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza',$user->getEmpresaActual());
        $pago = new Pago();
        $pago->setFechaRegistro(new \DateTime(date('Y-m-d H:i:s')));
        $pago->setUsuarioRegistro($user);
        $form = $this->createForm(PagoType::class, $pago);
       
        $form->add('fechaPago',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pago);
            $entityManager->flush();
            
            $this->asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago);
            
           
            return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
           
        }

        return $this->render('cobranza/new.html.twig', [
            'pago' => $pago,
            'contrato'=>$contrato,
            'pagina'=>"Agregar ".$pagina->getNombre(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="cobranza_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pago $pago,CuotaRepository $cuotaRepository,PagoCuotasRepository $pagoCuotasRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('edit','cobranza');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('cobranza',$user->getEmpresaActual());
        $pagoCuotas=$pago->getPagoCuotas();
        foreach($pagoCuotas as $pagoCuota){
            $cuota=$pagoCuota->getCuota();
            $contrato=$cuota->getContrato();
        }
        $form = $this->createForm(PagoType::class, $pago);
        $form->add('fechaRegistro',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->add('fechaPago',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $entityManager = $this->getDoctrine()->getManager();
            $contrato=null;
            $pagoCuotas=$pago->getPagoCuotas();
            foreach($pagoCuotas as $pagoCuota){
                $cuota=$pagoCuota->getCuota();
                $contrato=$cuota->getContrato();
                $cuota->setPagado($cuota->getPagado()-$pagoCuota->getMonto());
                $entityManager->remove($pagoCuota);
                $entityManager->flush();

            }

            $this->asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago);
            if(null != $contrato){
                return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
            }else{
                return $this->redirectToRoute('cobranza_index');
            }
        }

        return $this->render('cobranza/edit.html.twig', [
            'pago' => $pago,
            'contrato'=>$contrato,
            'form' => $form->createView(),
            'pagina'=>'Editar '.$pagina->getNombre(),
        ]);
    }
    /**
     * @Route("/{id}/terminar", name="cobranza_terminar", methods={"GET","POST"})
     */
    function terminar(Contrato $contrato,
                    DiasPagoRepository $diasPagoRepository,
                    ModuloPerRepository $moduloPerRepository,
                    ContratoFunciones $contratoFunciones,
                    Request $request): Response
    {
        $this->denyAccessUnlessGranted('create','cobranza');
        $user=$this->getUser();
        
        $pagina=$moduloPerRepository->findOneByName('cobranza',$user->getEmpresaActual());

        if(null !== $request->query->get('status')){
            $error_toast=$contratoFunciones->terminarContrato($contrato,$request->query->get('status'),$request->request->get('txtObservacion'));
           
            return $this->redirectToRoute('cobranza_index',['error_toast'=>$error_toast]);

        }else{
            $error_toast="Toast.fire({
                icon: 'danger',
                title: 'Error al desistir'
              })";
        }

        return $this->render('cobranza/show.html.twig', [
            'contrato' => $contrato,
            'agenda'=>$contrato->getAgenda(),
            'pagina'=>$pagina->getNombre(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'metodo'=>"T",
            
        ]);

    }
   
    /**
     * @Route("/{id}", name="cobranza_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pago $pago): Response
    {
        $this->denyAccessUnlessGranted('full','cobranza');
        $user=$this->getUser();
        if ($this->isCsrfTokenValid('delete'.$pago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $pago->setUsuarioAnulacion($user);
            $pago->setFechaAnulacion(new \DateTime(date("Y-m-d H:i")));
            $pago->setAnulado(true);
            $entityManager->persist($pago);
            $entityManager->flush();
            $contrato=null;
            $pagoCuotas=$pago->getPagoCuotas();
            foreach($pagoCuotas as $pagoCuota){
                $cuota=$pagoCuota->getCuota();
                $contrato=$cuota->getContrato();
                $cuota->setPagado($cuota->getPagado()-$pagoCuota->getMonto());
                $entityManager->remove($pagoCuota);
                $entityManager->flush();

            }
        }
        if(null != $contrato){
            return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
        }else{
            return $this->redirectToRoute('cobranza_index');
        }
    }


    public function asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago){
        $entityManager = $this->getDoctrine()->getManager();
        $contrato->setIsFinalizado(false);

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

        $cuota=$cuotaRepository->findOneByPrimeraVigente($contrato->getId());

        if($cuota){
            $contrato->setIsFinalizado(false);
        }else{
            $contrato->setIsFinalizado(true);
        }
        $entityManager->persist($contrato);
        $entityManager->flush();
        return true;
    }
}