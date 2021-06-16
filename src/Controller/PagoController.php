<?php

namespace App\Controller;

use App\Entity\Pago;
use App\Entity\Usuario;
use App\Entity\Cuota;
use App\Entity\PagoCuotas;
use App\Entity\Contrato;
use App\Entity\Importacion;
use App\Entity\PagoTipo;
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
use App\Repository\UsuarioRepository;
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
        $folio=null;
        $compania=null;
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
        
        return $this->render('pago/index.html.twig', [
            'cuotas' => $cuotas,
            'bFiltro'=>$filtro,
            'bFolio'=>$folio,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'finalizado'=>false,
        ]);
    }

    /**
     * @Route("/finalizado", name="pago_finalizado", methods={"GET"})
     */
    public function finalizado(ContratoRepository $contratoRepository, CuotaRepository $cuotaRepository,PagoRepository $pagoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago_finalizado',$user->getEmpresaActual());
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
     * @Route("/resumen", name="pago_resumen", methods={"GET"})
     */
    public function resumen(ContratoRepository $contratoRepository, CuotaRepository $cuotaRepository,PagoRepository $pagoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {

        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago_resumen',$user->getEmpresaActual());
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


        return $this->redirectToRoute('pago_index');
    }
    /**
     * @Route("/genera_cuotas", name="pago_generacuotas", methods={"GET","POST"})
     */
    public function generaCuotas(CuotaRepository $cuotaRepository,ContratoRepository $contratoRepository): Response
    {

        $contratos=$contratoRepository->findAll();


        foreach($contratos as $contrato){
            $cuota=$cuotaRepository->findOneByUltimaPagada($contrato->getId());
           
            if(null == $cuota){
                
                
                $entityManager = $this->getDoctrine()->getManager();

             

                $countCuotas=$contrato->getCuotas();
                $fechaPrimerPago=$contrato->getFechaPrimerPago();
                if($fechaPrimerPago){
                    
                    $diaPago=$contrato->getDiaPago();
                    $sumames=0;
                    $numeroCuota=1;
                    $isAbono=$contrato->getIsAbono();
                    if($isAbono){
                        $cuota=new Cuota();

                        $cuota->setContrato($contrato);
                        $cuota->setNumero($numeroCuota);
                        $cuota->setFechaPago($contrato->getFechaPrimeraCuota());
                        $cuota->setMonto($contrato->getPrimeraCuota());

                        $entityManager->persist($cuota);
                        $entityManager->flush();
                        $numeroCuota++;
                    }
                    $primerPago=date("Y-m-".$diaPago,strtotime($fechaPrimerPago->format('Y-m-d')));
                    if(date("n",strtotime($fechaPrimerPago->format('Y-m-d')))==2){
                        if($diaPago==30)
                            $primerPago=date("Y-m-28",strtotime($fechaPrimerPago->format('Y-m-d')));

                    }
                
                    $timePrimrePago=strtotime($primerPago);
                    //fechaActual debe ser fecha_creacion:::
                    $timeFechaActual=strtotime($contrato->getFechaCreacion()->format('Y-m-d'));
                
                
                    if($timeFechaActual>=$timePrimrePago){

                        $sumames=1;
                    }
                    for($i=0;$i<$countCuotas;$i++){
                        $cuota=new Cuota();
                
                        $i_aux=$i;
                    
                        $cuota->setContrato($contrato);
                        $cuota->setNumero($numeroCuota);

                        $ts = mktime(0, 0, 0, date('m',$timePrimrePago) + $sumames+$i_aux, 1,date('Y',$timePrimrePago));
                        
                        $dia=$diaPago;
                        if(date("n",$ts)==2){
                            if($diaPago==30){
                                $dia=date("d",mktime(0,0,0,date('m',$timePrimrePago)+ $sumames+$i_aux+1,1,date('Y',$timePrimrePago))-24);
                            }
                        }
                        $fechaCuota=date("Y-m-d", mktime(0,0,0,date('m',$timePrimrePago) + $sumames+$i_aux,$dia,date('Y',$timePrimrePago)));
                        $cuota->setFechaPago(new \DateTime($fechaCuota));
                        $cuota->setMonto($contrato->getValorCuota());

                        $entityManager->persist($cuota);
                        $entityManager->flush();
                        $numeroCuota++;
                    }
                }
            }
        }
        return $this->redirectToRoute('pago_index');
    }
    /**
     * @Route("/cargar_pagos", name="pago_cargarpagos", methods={"GET","POST"})
     */
    public function cargarPagos(Request $request,
                                PagoRepository $pagoRepository,
                                PagoTipoRepository $pagoTipoRepository,
                                CuentaCorrienteRepository $cuentaCorrienteRepository,
                                PagoCanalRepository $pagoCanalRepository,
                                CuotaRepository $cuotaRepository,
                                PagoCuotasRepository $pagoCuotasRepository,
                                ContratoRepository $contratoRepository,
                                UsuarioRepository $usuarioRepository):Response
    {
        $user=$this->getUser();
        $importacion = new Importacion();
        $importacion->setFechaCarga(new \DateTime(date("Y-m-d H:i:s")));
        $form = $this->createForm(ImportacionType::class, $importacion);
        $form->add('cuenta');
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('url')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('csv_importacion'),
                        $newFilename
                    );
                    $importacion->setNombre($originalFilename);
                    $importacion->setUrl($this->getParameter('csv_importacion').$newFilename);
                    $importacion->setUsuarioCarga($usuarioRepository->find($user->getId()));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($importacion);
                    $entityManager->flush();
                    $fp = fopen($importacion->getUrl(), "r");
                    $i=0;
                    $paso=true;
                    $mensajeError="";
                  
            
            
                    while (!feof($fp)){
                        $linea = fgets($fp);
                        $datos=explode(";",$linea);
                        if ($i==0){
                            $i++;
                            continue;
                        }
                        $i++;
                        
                        if($datos[0]=="") break;

                        $pago=new Pago();
                        $pago->setPagoTipo($pagoTipoRepository->find($datos[1]));
                        $pago->setPagoCanal($pagoCanalRepository->find($datos[2]));
                        $pago->setMonto($datos[3]);
                        $pago->setBoleta($datos[4]);
                        $pago->setObservacion($datos[5]);
                        $pago->setFechaPago(new \DateTime(date('Y-m-d',strtotime($datos[6]))));
                        $pago->setHoraPago(new \DateTime(date('H:i',strtotime($datos[7]))));
                        $pago->setFechaRegistro(new \DateTime(date('Y-m-d H:i',strtotime($datos[8]))));
                        $pago->setCuentaCorriente($cuentaCorrienteRepository->find($datos[9]));
                        $pago->setNcomprobante($datos[10]);
                        $pago->setComprobante($datos[11]);
                        $pago->setUsuarioRegistro($datos[12]);
                        $entityManager->persist($pago);
                        $entityManager->flush();

                        $this->asociarPagos($contratoRepository->find($datos[0]),$cuotaRepository,$pagoCuotasRepository,$pago);
                    }
                } catch (FileException $e) {
                }
            }
        }
        return $this->render('pago/cargarpagos.html.twig', [
            'importacion' => $importacion,
            'form' => $form->createView(),
            'pagina'=>"Cargar Pagos",
        ]);
    }
    /**
     * @Route("/{id}", name="pago_show", methods={"GET"})
     */
    public function show(Pago $pago): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
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
     * @Route("/{id}/verpagos", name="verpagos_index", methods={"GET","POST"})
     */
    public function verpagos(Request $request, Contrato $contrato,CuotaRepository $cuotaRepository, PagoRepository $pagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','pago');
        $user=$this->getUser();$error_toast="";
        if(null !== $request->query->get('error_toast')){
            $error_toast=$request->query->get('error_toast');
        }

        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $pagos=$pagoRepository->findByContrato($contrato);

        $cuotas_multa=$cuotaRepository->findOneByPrimeraVigente($contrato->getId(),true);
        $pagos_multa=$pagoRepository->findByContrato($contrato,true);

        return $this->render('pago/verpagos.html.twig', [
            'pagos' => $pagos,
            'pagina'=>"Ingreso ". $pagina->getNombre(),
            'contrato'=>$contrato,
            'cuotas_multa'=>$cuotas_multa,
            'pagos_multa'=>$pagos_multa,
            
            'error_toast'=>$error_toast,
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
     * @Route("/{id}/new", name="pago_new", methods={"GET","POST"})
     */
    public function new(Request $request,
                        Contrato $contrato,
                        CuotaRepository $cuotaRepository,
                        PagoCuotasRepository $pagoCuotasRepository,
                        PagoTipoRepository $pagoTipoRepository,
                        ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('create','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $entityManager = $this->getDoctrine()->getManager();
        $tipoPago=false;
        if(isset($_POST['cboTipo']))
            $tipoPago=$_POST['cboTipo'];
        
            

        $pago = new Pago();
        if($tipoPago){
            $tipo=$pagoTipoRepository->find($tipoPago);
            $pago->setPagoTipo($tipo);
            $pago->setComprobante("nodisponible.png");
        }
        $pago->setFechaRegistro(new \DateTime(date('Y-m-d H:i:s')));
        $pago->setUsuarioRegistro($user);
        $form = $this->createForm(PagoType::class, $pago);
    
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fechaPago=$request->request->get('fechaPago');
            
            $pago->setFechaPago(new \DateTime(date('Y-m-d H:i',strtotime($fechaPago))));
            $pago->setHoraPago(new \DateTime(date('H:i',strtotime($fechaPago))));
            $entityManager->persist($pago);
            $entityManager->flush();
            
            $pagoCuotasRepository->asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago);
            
        
            return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
        
        }
        
        if($tipoPago){
            return $this->render('pago/new.html.twig', [
                'pago' => $pago,
                'contrato'=>$contrato,
                'pagina'=>"Agregar ".$pagina->getNombre(),
                'form' => $form->createView(),
                'isBoucher'=>$tipo->getIsBoucher(),
                'etapa'=>1,
            ]);
        }else{

            return $this->render('pago/tipoPago.html.twig', [
                
                'contrato'=>$contrato,
                'pagoTipos'=>$pagoTipoRepository->findAll(),
             ] );
        }
        
    }


    /**
     * @Route("/{id}/edit", name="pago_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pago $pago,CuotaRepository $cuotaRepository,PagoCuotasRepository $pagoCuotasRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('edit','pago');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('pago',$user->getEmpresaActual());
        $pagoCuotas=$pago->getPagoCuotas();
        foreach($pagoCuotas as $pagoCuota){
            $cuota=$pagoCuota->getCuota();
            $contrato=$cuota->getContrato();
        }
        $form = $this->createForm(PagoType::class, $pago);
        //$form->add('fechaRegistro',DateType::class,array('widget'=>'single_text','html5'=>false));
        //$form->add('fechaPago',DateType::class,array('widget'=>'single_text','html5'=>false));
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

            $pagoCuotasRepository->asociarPagos($contrato,$cuotaRepository,$pagoCuotasRepository,$pago);
            if(null != $contrato){
                return $this->redirectToRoute('verpagos_index',['id'=>$contrato->getId()]);
            }else{
                return $this->redirectToRoute('pago_index');
            }
        }

        return $this->render('pago/edit.html.twig', [
            'pago' => $pago,
            'contrato'=>$contrato,
            'form' => $form->createView(),
            'pagina'=>'Editar '.$pagina->getNombre(),
            'etapa'=>2,
        ]);
    }
    /**
     * @Route("/{id}/isboucher", name="pago_isboucher", methods={"GET","POST"})
     */
    public function isBoucher(Request $request,PagoTIpo $pagoTipo):Response
    {
        if($pagoTipo->getIsBoucher()){
            return $this->json(['isBoucher'=>true]);
        }else{
            return $this->json(['isBoucher'=>false]);
        }
    }
    
    /**
     * @Route("/{id}", name="pago_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pago $pago): Response
    {
        $this->denyAccessUnlessGranted('full','pago');
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
            return $this->redirectToRoute('pago_index');
        }
    }
}
