<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\ContratoRol;
use App\Entity\Usuario;
use App\Entity\Cuota;
use App\Entity\Region;
use App\Entity\Ciudad;
use App\Entity\Comuna;
use App\Form\ContratoType;
use App\Entity\AgendaObservacion;
use App\Entity\ContratoMee;
use App\Form\ContratoRolType;
use App\Repository\ContratoRepository;
use App\Repository\ContratoRolRepository;
use App\Repository\JuzgadoRepository;
use App\Repository\SucursalRepository;
use App\Repository\CuentaRepository;
use App\Repository\DiasPagoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\UsuarioTipoRepository;
use App\Repository\AgendaStatusRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\CuotaRepository;
use App\Repository\ConfiguracionRepository;
use App\Repository\LotesRepository;
use App\Repository\RegionRepository;
use App\Repository\CiudadRepository;
use App\Repository\ComunaRepository;
use App\Repository\ContratoMeeRepository;
use App\Repository\MeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ContratoFunciones;
/**
 * @Route("/contrato")
 */
class ContratoController extends AbstractController
{
    /**
     * @Route("/", name="contrato_index", methods={"GET","POST"})
     */
    public function index(ContratoRepository $contratoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        $filtro=null;
        $error='';
        $error_toast="";
        $otros="";
        $folio="";
        if(null !== $request->query->get('error_toast')){
            $error_toast=$request->query->get('error_toast');
        }
        $compania=null;
        if(null !== $request->query->get('bFolio') && $request->query->get('bFolio')!=''){
            $folio=$request->query->get('bFolio');
            $otros=" c.folio= $folio";

            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');
            $fecha=$otros. " and a.status in (7,14)";

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
            $fecha="c.fechaCreacion between '$dateInicio' and '$dateFin 23:59:59' and a.status in (7,14)" ;
        }
      
        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 4:
            case 1:
            case 13:
                $query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            case 7:
                $query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha." and c.tramitador = ".$user->getId());
                $companias=$cuentaRepository->findByPers($user->getId());
                break;
            case 12://Cobradores
                $lotes;
                foreach($user->getUsuarioLotes() as $usuarioLote){
                    $lotes[]=$usuarioLote->getLote()->getId();
                }
                if(count($lotes)>0){
                    $fecha.=" and c.idLote in (".implode(",",$lotes).") ";
                }else{
                    $fecha.=" and c.idLote is null ";
                }
                //$fecha.=" and c.idLote in (".implode(",",$lotes).") ";
                $query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                break;
            default:
                $query=$contratoRepository->findByPers($user->getId(),null,$compania,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers($user->getId());
                
            break;
        }
        //$companias=$cuentaRepository->findByPers($user->getId());
        //$query=$contratoRepository->findAll();
        $contratos=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));
        return $this->render('contrato/index.html.twig', [
            'contratos' => $contratos,
            'bFiltro'=>$filtro,
            'bFolio'=>$folio,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'error'=>$error,
            'error_toast'=>$error_toast,
        ]);
    }

     /**
     * @Route("/actualizafecha", name="contrato_actualizaFecha", methods={"GET","POST"})
     */
    public function actualizafecha(Request $request,ContratoRepository $contratoRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $contratos=$contratoRepository->findAll();
        foreach($contratos as $contrato){
            $agenda=$contrato->getAgenda();
            $agenda->setFechaContrato($contrato->getFechaCreacion());
            $entityManager->persist($agenda);
            $entityManager->flush();
        }
        return $this->redirectToRoute('contrato_index');
    }
    /**
     * @Route("/new", name="contrato_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('create','contrato');
        $contrato = new Contrato();
        $form = $this->createForm(ContratoType::class, $contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contrato);
            $entityManager->flush();

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('contrato/new.html.twig', [
            'contrato' => $contrato,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/regenerapdfs", name="contrato_regenerapdfs", methods={"GET","POST"})
     */
    public function regenerapdfs(\Knp\Snappy\Pdf $snappy,ContratoRepository $contratoRepository): Response
    {
        $this->denyAccessUnlessGranted('edit','contrato');

        $contratos=$contratoRepository->findBy(['pdf'=>null]);

        foreach($contratos as $contrato){
            $filename = sprintf('Contrato-'.$contrato->getId().'-%s.pdf',rand(0,9000));
        
            $html = $this->renderView('contrato/print.html.twig', array(
                'contrato' => $contrato,
                'Titulo'=>"Contrato"
            ));

            $entityManager = $this->getDoctrine()->getManager();
            $contrato->setPdf($filename);
            $entityManager->persist($contrato);
            $entityManager->flush();

            $snappy->generateFromHtml(
            $html,
            $this->getParameter('url_root'). $this->getParameter('pdf_contratos').$filename
            );




            
        }
        return $this->redirectToRoute('contrato_index');
    }
    /**
     * @Route("/{id}", name="contrato_show", methods={"GET"})
     */
    public function show(Contrato $contrato,DiasPagoRepository $diasPagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        return $this->render('contrato/show.html.twig', [
            'contrato' => $contrato,
            'agenda'=>$contrato->getAgenda(),
            'pagina'=>$pagina->getNombre(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            
        ]);
    }

    /**
     * @Route("/{id}/new_rol", name="contrato_new_rol", methods={"GET","POST"})
     */
    public function newRol(Contrato $contrato,Request $request,JuzgadoRepository $juzgadoRepository,ContratoRolRepository $contratoRolRepository): Response
    {
        
        $user=$this->getUser();
        if (null==$request->query->get('mode')){
            $mode='edit';
        }else{
            $mode=$request->query->get('mode');
        }
        
        $contrato_rol = new ContratoRol();
        $contrato_rol->setContrato($contrato);
        $abogado=$this->getDoctrine()->getRepository(Usuario::class)->find($user->getId());
        $contrato_rol->setAbogado($abogado);

        if(isset($_GET['nombre'])){
            $contrato_rol->setNombreRol($_GET['nombre']);
            $contrato_rol->setInstitucionAcreedora($_GET['institucion']);
            $contrato_rol->setJuzgado($juzgadoRepository->find($_GET['juzgado']));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contrato_rol);
            $entityManager->flush();

        }
        return $this->render('contrato/contratoRoles.html.twig', [
            'contrato_rols' => $contratoRolRepository->findBy(['contrato'=>$contrato->getId()]),
            'mode'=>$mode,
           
        ]);
    }
    
    /**
     * @Route("/{id}/del_rol", name="contrato_del_rol",  methods={"DELETE"})
     */
    public function delRol(ContratoRol $contratoRol,Request $request,JuzgadoRepository $juzgadoRepository,ContratoRolRepository $contratoRolRepository): Response
    {
        
        $user=$this->getUser();

        $contrato=$contratoRol->getContrato();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($contratoRol);
        $entityManager->flush();

        
        return $this->render('contrato/contratoRoles.html.twig', [
            'contrato_rols' => $contratoRolRepository->findBy(['contrato'=>$contrato->getId()]),
           
        ]);
    }
    /**
     * @Route("/{id}/edit", name="contrato_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, 
                    Contrato $contrato,
                    JuzgadoRepository $juzgadoRepository,
                    SucursalRepository $sucursalRepository,
                    DiasPagoRepository $diasPagoRepository,
                    UsuarioRepository $usuarioRepository,
                    CuentaRepository $cuentaRepository,
                    ModuloPerRepository $moduloPerRepository,
                    CuotaRepository $cuotaRepository,
                    RegionRepository $regionRepository,
                    ComunaRepository $comunaRepository,
                    CiudadRepository $ciudadRepository,
                    MeeRepository $meeRepository,
                    \Knp\Snappy\Pdf $snappy): Response
    {
        $this->denyAccessUnlessGranted('edit','contrato');
        $user=$this->getUser();

        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        $juzgados=$juzgadoRepository->findAll();
        $form = $this->createForm(ContratoType::class, $contrato);
        $form->add('fechaPrimeraCuota',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->add('vigencia');
        $form->handleRequest($request);
        //buscamos la primera cuota para sabes si tiene algun pago asociado:::
        $cuota=$cuotaRepository->findOneByUltimaPagada($contrato->getId());
        $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
        
        $tienePago=false;
        if(null != $cuota){
            foreach( $cuota->getPagoCuotas() as $pago){
                $tienePago=true;
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $compania=$_POST['cboCompanias'];
            $contrato->setSucursal($sucursalRepository->find($request->request->get('cboSucursal')));
            $contrato->setDiaPago($request->request->get('chkDiasPago'));
            $contrato->setTramitador($usuarioRepository->find($request->request->get('cboTramitador')));
            $contrato->setFechaPrimerPago(new \DateTime(date($request->request->get('txtFechaPago')."-1 00:00:00")));

            $contrato->setCregion($regionRepository->find($request->request->get('cboRegion')));
            $contrato->setCciudad($ciudadRepository->find($request->request->get('cboCiudad')));
            $contrato->setCcomuna($comunaRepository->find($request->request->get('cboComuna')));
            $contrato->setSexo($request->request->get('cboSexo'));
            $entityManager = $this->getDoctrine()->getManager();
            $contrato->setPdf(null);
            $entityManager->persist($contrato);
            $entityManager->flush();
            
            $agenda=$contrato->getAgenda();
            $agenda->setNombreCliente($contrato->getNombre());
            $agenda->setTelefonoCliente($contrato->getTelefono());
            $agenda->setEmailCliente($contrato->getEmail());
            $agenda->setReunion($contrato->getReunion());
            $agenda->setCuenta($cuentaRepository->find($compania));
            $entityManager->persist($agenda);
            $entityManager->flush();


            $contratoMees=$contrato->getContratoMees();

            foreach($contratoMees as $contratoMee){
                $entityManager->remove($contratoMee);
                $entityManager->flush();
            }
            
            $mees=$_POST['mee'];
            foreach($mees as $mee ){
                
                $contratoMee = new ContratoMee();
                $contratoMee->setContrato($contrato);
                $contratoMee->setMee($meeRepository->find($mee));
                $entityManager->persist($contratoMee);
                $entityManager->flush();
            }


            if(!$tienePago){
                $detalleCuotas=$contrato->getDetalleCuotas();
                foreach($detalleCuotas as $detalleCuota){
                // $contrato->removeDetalleCuota($detalleCuota);
                    $entityManager->remove($detalleCuota);
                    $entityManager->flush();
                }
                

                $countCuotas=$contrato->getCuotas();
                $fechaPrimerPago=$contrato->getFechaPrimerPago();
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

                $timeFechaActual=strtotime(date("Y-m-d"));
            
            
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
            
            
            
           
            
            return $this->redirectToRoute('contrato_pdf',['id'=>$contrato->getId()]);
        }
               

        return $this->render('contrato/edit.html.twig', [
            'contrato' => $contrato,
            'companias'=>$companias,
            'tienePago'=>$tienePago,
            'agenda'=>$contrato->getAgenda(),
            'form' => $form->createView(),
            'juzgados'=>$juzgados,
            'pagina'=>$pagina->getNombre()." N° ".$contrato->getFolio(),
            'tramitadores'=>$usuarioRepository->findByCuenta($contrato->getAgenda()->getCuenta()->getId(),['usuarioTipo'=>7,'estado'=>1]),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'sucursales'=>$sucursalRepository->findBy(['cuenta'=>$contrato->getAgenda()->getCuenta()->getId()]),
            'regiones'=>$regionRepository->findAll(),
            'cuenta_materias'=>$contrato->getAgenda()->getCuenta()->getCuentaMaterias(),
            'contratoMees'=>$contrato->getContratoMees(),
        ]);
    }

    /**
     * @Route("/{id}/finalizar", name="contrato_finalizar", methods={"GET","POST"})
     */
    public function finalizar(Request $request, 
                            Contrato $contrato,
                            JuzgadoRepository $juzgadoRepository,
                            SucursalRepository $sucursalRepository,
                            DiasPagoRepository $diasPagoRepository,
                            UsuarioRepository $usuarioRepository,
                            UserPasswordEncoderInterface $encoder,
                            UsuarioTipoRepository $usuarioTipoRepository,
                            ConfiguracionRepository $configuracionRepository,
                            ContratoRepository $contratoRepository,
                            ContratoFunciones $contratoFunciones,
                            CuentaRepository $cuentaRepository,
                            LotesRepository $lotesRepository,
                            RegionRepository $regionRepository,
                            ComunaRepository $comunaRepository,
                            CiudadRepository $ciudadRepository,
                            MeeRepository $meeRepository
                            ): Response
    {
        $this->denyAccessUnlessGranted('create','panel_abogado');
        $user=$this->getUser();
        $juzgados=$juzgadoRepository->findAll();
        $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
        
        $form = $this->createForm(ContratoType::class, $contrato);
        $form->add('fechaPrimeraCuota',DateType::class,array('widget'=>'single_text','html5'=>false));
        $form->add('vigencia');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $configuracion=$configuracionRepository->find(1);
            $entityManager = $this->getDoctrine()->getManager();


            
            $contrato->setCregion($regionRepository->find($request->request->get('cboRegion')));
            $contrato->setCciudad($ciudadRepository->find($request->request->get('cboCiudad')));
            $contrato->setCcomuna($comunaRepository->find($request->request->get('cboComuna')));
            $contrato->setSexo($request->request->get('cboSexo'));

            
            $contratoMees=$contrato->getContratoMees();

            foreach($contratoMees as $contratoMee){
                $entityManager->remove($contratoMee);
                $entityManager->flush();
            }
            
            $mees=$_POST['mee'];
            foreach($mees as $mee ){
                
                $contratoMee = new ContratoMee();
                $contratoMee->setContrato($contrato);
                $contratoMee->setMee($meeRepository->find($mee));
                $entityManager->persist($contratoMee);
                $entityManager->flush();
            }


            //configuramos el Lote al cual caera::
            //$ult_contrato=$contratoRepository->findLoteMax($user->getEmpresaActual());
            $lote=$lotesRepository->findPrimerDisponible();
            if(null == $lote){
                $lotes=$lotesRepository->findBy(['empresa'=>$user->getEmpresaActual()]);
                foreach($lotes as $lote){
                    $lote->setIsUtilizado(false);
                    $entityManager->persist($lote);
                    $entityManager->flush();
                }
                $lote=$lotesRepository->findPrimerDisponible();

            }else{
                $lote->setIsUtilizado(true);
                $entityManager->persist($lote);
                $entityManager->flush();
            }


            $contrato->setDiaPago($request->request->get('chkDiasPago'));
            $contrato->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
            $contrato->setSucursal($sucursalRepository->find($request->request->get('cboSucursal')));
            $contrato->setTramitador($usuarioRepository->find($request->request->get('cboTramitador')));
            $contrato->setIdLote($lote);
            
            $agenda=$contrato->getAgenda();

            $usuario=$usuarioRepository->findOneBy(['username'=>$contrato->getEmail()]);
            if(!$usuario){
                $usuario=new Usuario();
                $usuario->setUsername($contrato->getEmail());
                $password=$usuario->getPassword();
                $encoded=$encoder->encodePassword($usuario,$password);
                $usuario->setPassword($encoded);
                $usuario->setCorreo($contrato->getEmail());
                $usuario->setNombre($contrato->getNombre());
                $usuario->setTelefono($contrato->getTelefono());
                $usuario->setEstado(1);
                $usuario->setFechaActivacion(new \DateTime(date("Y-m-d H:i:s")));
                $usuario->setUsuarioTipo($usuarioTipoRepository->find(9));
                $usuario->setEmpresaActual($agenda->getCuenta()->getEmpresa()->getId());

                $entityManager->persist($usuario);
                $entityManager->flush();
            }

            $contrato->setCliente($usuario);
            $entityManager->persist($contrato);
            $entityManager->flush();
            $agenda->setNombreCliente($contrato->getNombre());
            $agenda->setTelefonoCliente($contrato->getTelefono());
            $agenda->setEmailCliente($contrato->getEmail());
            $agenda->setReunion($contrato->getReunion());
            $entityManager->persist($agenda);
            $entityManager->flush();

            $countCuotas=$contrato->getCuotas();
            $fechaPrimerPago=$contrato->getFechaPrimerPago();
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
         
            $timePrimerPago=strtotime($primerPago);

            $timeFechaActual=strtotime(date("Y-m-d"));

            if($timeFechaActual>=$timePrimerPago){

                $sumames=1;
            }
            for($i=0;$i<$countCuotas;$i++){
                $cuota=new Cuota();
                $i_aux=$i;
                
                $cuota->setContrato($contrato);
                $cuota->setNumero($numeroCuota);

                $ts = mktime(0, 0, 0, date('m',$timePrimerPago) + $sumames+$i_aux, 1,date('Y',$timePrimerPago));
                $dia=$diaPago;
                if(date("n",$ts)==2){
                    if($dia==30){
                        $dia=date("d",mktime(0,0,0,date('m',$timePrimerPago)+ $sumames+$i_aux+1,1,date('Y',$timePrimerPago))-24);
                    }
                }
                $fechaCuota=date("Y-m-d", mktime(0,0,0,date('m',$timePrimerPago) + $sumames+$i_aux,$dia,date('Y',$timePrimerPago)));
                $cuota->setFechaPago(new \DateTime($fechaCuota));
                $cuota->setMonto($contrato->getValorCuota());

                $entityManager->persist($cuota);
                $entityManager->flush();
                $numeroCuota++;
            }
            return $this->redirectToRoute('contrato_pdf',['id'=>$contrato->getId()]);
        }

        return $this->render('contrato/finalizar.html.twig', [
            'contrato' => $contrato,
            'agenda'=> $contrato->getAgenda(),
            'companias'=>$companias,
            'form' => $form->createView(),
            'tienePago'=>false,
            'juzgados'=>$juzgados,
            'pagina'=>"Revise los datos para finalizar",
            'tramitadores'=>$usuarioRepository->findByCuenta($contrato->getAgenda()->getCuenta()->getId(),['usuarioTipo'=>7,'estado'=>1]),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'sucursales'=>$sucursalRepository->findBy(['cuenta'=>$contrato->getAgenda()->getCuenta()->getId()]),
            'regiones'=>$regionRepository->findAll(),
            'cuenta_materias'=>$contrato->getAgenda()->getCuenta()->getCuentaMaterias(),
            'contratoMees'=>$contrato->getContratoMees(),
        ]);
    }

   
    /**
     * @Route("/{id}/pdf", name="contrato_pdf", methods={"GET","POST"})
     */
    public function pdf(Contrato $contrato,ContratoMeeRepository $contratoMeeRepository)
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $filename = sprintf('Contrato-'.$contrato->getId().'-%s.pdf',rand(0,9000));
       
        $html = $this->renderView('contrato/print.html.twig', array(
            'contrato' => $contrato,
            'Titulo'=>"Contrato",
            'mees'=>$contratoMeeRepository->findByContrato($contrato->getId()),
        ));

        $entityManager = $this->getDoctrine()->getManager();
        $contrato->setPdf($filename);
        $entityManager->persist($contrato);
        $entityManager->flush();

       


        // Configure Dompdf según sus necesidades
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'helvetica');
    
        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);

        $dompdf->getOptions()->setChroot(array($this->getParameter('url_raiz')));
        
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);
        
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        $dompdf->setPaper('letter', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();

        $file=$dompdf->output();
        file_put_contents($this->getParameter('url_root'). $this->getParameter('pdf_contratos').$filename,$file);
        // Envíe el PDF generado al navegador (descarga forzada)
        /*$dompdf->stream($filename, [
            "Attachment" => true
        ]);*/

        return $this->redirectToRoute('contrato_index');
    }
    /**
     * @Route("/{id}/terminar", name="contrato_terminar", methods={"GET","POST"})
     */
    function terminar(Contrato $contrato,
                    DiasPagoRepository $diasPagoRepository,
                    ModuloPerRepository $moduloPerRepository,
                    Request $request,
                    ContratoFunciones $contratoFunciones): Response
    {
        $this->denyAccessUnlessGranted('create','terminos');
        $user=$this->getUser();
        
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());

        if(null !== $request->query->get('status')){
            $error_toast=$contratoFunciones->terminarContrato($contrato,$request->query->get('status'),$request->request->get('txtObservacion'));
           
            return $this->redirectToRoute('contrato_index',['error_toast'=>$error_toast]);

        }
        
        return $this->render('contrato/show.html.twig', [
            'contrato' => $contrato,
            'agenda'=>$contrato->getAgenda(),
            'pagina'=>$pagina->getNombre(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'metodo'=>"T",
            
        ]);
    }

     /**
     * @Route("/{id}/ciudad", name="contrato_ciudad", methods={"GET","POST"})
     */
    function ciudad(Region $region, CiudadRepository $ciudadRepository,Request $request): Response
    {
        $ciudad_def=null;
       
        if(null != $request->query->get('ciudad')){
            $ciudad_def=$request->query->get('ciudad');
        }
        $ciudades=$ciudadRepository->findBy(['region'=>$region->getId()]);
        return $this->render('contrato/_ciudades.html.twig', [
            'ciudades' => $ciudades,
            'ciudad_def'=>$ciudad_def,
        ]);
    }

    /**
     * @Route("/{id}/comuna", name="contrato_comuna", methods={"GET","POST"})
     */
    function comuna(Ciudad $ciudad, ComunaRepository $comunaRepository,Request $request): Response
    {
        $comuna_def=null;
       
        if(null != $request->query->get('comuna')){
            $comuna_def=$request->query->get('comuna');
        }
        $comunas=$comunaRepository->findBy(['ciudad'=>$ciudad->getId()]);
        return $this->render('contrato/_comunas.html.twig', [
            'comunas' => $comunas,
            'comuna_def'=>$comuna_def,
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contrato $contrato,AgendaStatusRepository $agendaStatusRepository): Response
    {
        $this->denyAccessUnlessGranted('full','contrato');
        if ($this->isCsrfTokenValid('delete'.$contrato->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $agenda=$contrato->getAgenda();
            $agenda->setStatus($agendaStatusRepository->find('5'));

            $entityManager->persist($agenda);
            $entityManager->flush();

            $contrato->setAgenda(null);
            $entityManager->persist($contrato);
            $entityManager->flush();
            $contratoRoles=$contrato->getContratoRols();
            foreach($contratoRoles as $contratoRol){
                $contrato->removeContratoRol($contratoRol);
            }
            $entityManager->remove($contrato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrato_index');
    }
    
    public function pdf2(Contrato $contrato)
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $filename = sprintf('Contrato-'.$contrato->getId().'-%s.pdf',rand(0,9000));
       
        $html = $this->renderView('contrato/print.html.twig', array(
            'contrato' => $contrato,
            'Titulo'=>"Contrato"
        ));

        $entityManager = $this->getDoctrine()->getManager();
        $contrato->setPdf($filename);
        $entityManager->persist($contrato);
        $entityManager->flush();

        /*$snappy->generateFromHtml(
           $html,
           $this->getParameter('url_root'). $this->getParameter('pdf_contratos').$filename
        );
        return new PdfResponse(
            $snappy->getOutputFromHtml($html, array(
                'page-size' => 'letter')),
            $filename
        );*/


        // Configure Dompdf según sus necesidades
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'helvetica');
    
        //$pdfOptions->set('fontHeightRatio',0.1);
        
        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);

        $dompdf->getOptions()->setChroot(array($this->getParameter('url_raiz')));
        
        // Recupere el HTML generado en nuestro archivo twig
       /* $html = $this->renderView('default/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);*/
        
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);
        
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        $dompdf->setPaper('letter', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();

        $file=$dompdf->output();
        file_put_contents($this->getParameter('url_root'). $this->getParameter('pdf_contratos').$filename,$file);
        // Envíe el PDF generado al navegador (descarga forzada)
        /*$dompdf->stream($filename, [
            "Attachment" => true
        //]);*/
    }
}
