<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Agenda;
use App\Entity\Usuario;
use App\Entity\ContratoRol;
use App\Entity\AgendaObservacion;
use App\Entity\Contrato;
use App\Form\ContratoType;
use App\Repository\AgendaRepository;
use App\Repository\JuzgadoRepository;
use App\Repository\ContratoRepository;
use App\Repository\ContratoRolRepository;
use App\Repository\UsuarioRepository;
use App\Repository\UsuarioTipoRepository;
use App\Repository\AgendaStatusRepository;
use App\Repository\SucursalRepository;
use App\Repository\CuentaRepository;
use App\Repository\ModuloRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\DiasPagoRepository;
use App\Repository\ReunionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
     * @Route("/panel_abogado")
     */
class PanelAbogadoController extends AbstractController
{
    /**
     * @Route("/", name="panel_abogado_index", methods={"GET","POST"})
     */
    public function index(AgendaRepository $agendaRepository,
                        CuentaRepository $cuentaRepository,
                        PaginatorInterface $paginator,
                        Request $request,
                        ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','panel_abogado');

        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('panel_abogado',$user->getEmpresaActual());
        $filtro=null;
        $compania=null;
        $fecha=null;
        $statues='4,5,6';
        $statuesgroup='4,5,7,6,8,9,10';
        $status=null;
        if(null !== $request->query->get('bFiltro') && trim($request->query->get('bFiltro'))!=''){
            $filtro=$request->query->get('bFiltro');
        }
        if(null !== $request->query->get('bCompania')&&$request->query->get('bCompania')!=0){
            $compania=$request->query->get('bCompania');
        }

        if(null !== $request->query->get('bFecha')){
            $aux_fecha=explode(" - ",$request->query->get('bFecha'));
            $dateInicio=$aux_fecha[0];
            $dateFin=$aux_fecha[1];
            $statues=$statuesgroup;
        }else{
            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');

        }
        $fecha="a.fechaAsignado between '$dateInicio' and '$dateFin 23:59:59'" ;
        
        if(null !== $request->query->get('bStatus') && trim($request->query->get('bStatus')!='')){
            $status=$request->query->get('bStatus');
            $statues=$status;
            $statuesgroup=$status;
        }

        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 4:
            case 1:
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,$statues,$filtro,1,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                $queryresumen=$agendaRepository->findByPersGroup(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,1,$fecha);
                
            break;
            default:
                $query=$agendaRepository->findByPers($user->getId(),null,$compania,$statues,$filtro,1,$fecha);
                $companias=$cuentaRepository->findByPers($user->getId());
                $queryresumen=$agendaRepository->findByPersGroup($user->getId(),null,$compania,$statuesgroup,$filtro,1,$fecha);
            break;
        }

        
        $agendas=$paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        20 /*limit per page*/,
        array('defaultSortFieldName' => 'fechaAsignado', 'defaultSortDirection' => 'asc'));

        return $this->render('panel_abogado/index.html.twig', [
            'agendas' => $agendas,
            'pagina'=>$pagina->getNombre(),
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'resumenes'=>$queryresumen,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'status'=>$status,
        ]);
    }
    /**
     * @Route("/new_rol", name="panel_abogado_new_rol", methods={"GET","POST"})
     */
    public function newRol(Request $request,
                            JuzgadoRepository $juzgadoRepository,
                            ContratoRolRepository $contratoRolRepository,
                            ModuloPerRepository $moduloPerRepository): Response
    {
        
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('panel_abogado',$user->getEmpresaActual());
        $contrato_rol = new ContratoRol();
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
        return $this->render('panel_abogado/contratoRoles.html.twig', [
            'pagina'=>$pagina->getNombre(),
            'contrato_rols' => $contratoRolRepository->findByTemporal($abogado->getId()),
           
        ]);
    }
    /**
     * @Route("/{id}", name="panel_abogado_new", methods={"GET","POST"})
     */
    public function new(Agenda $agenda,
                        AgendaRepository $agendaRepository,
                        AgendaStatusRepository $agendaStatusRepository,
                        CuentaRepository $cuentaRepository,
                        UsuarioRepository $usuarioRepository,
                        ReunionRepository $reunionRepository,
                        Request $request,
                        ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('create','panel_abogado');

        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('panel_abogado',$user->getEmpresaActual());
        if(null != $request->request->get('chkStatus')){
            $agenda->setStatus($agendaStatusRepository->find($request->request->get('chkStatus')));
            if(null !== $request->request->get('cboAbogado')){
                $agenda->setAbogado($usuarioRepository->find($request->request->get('cboAbogado')));
            }
            if(null !== $request->request->get('txtCiudad')){
                $agenda->setCiudadCliente($request->request->get('txtCiudad'));
            }
            if(null !== $request->request->get('txtFechaAgendamiento')){
                $agenda->setFechaAsignado(new \DateTime($request->request->get('txtFechaAgendamiento')." ".$request->request->get('cboHoras').":00"));
            }
            if(null !== $request->request->get('txtMonto')){
                $agenda->setMonto($request->request->get('txtMonto'));
            }
            if(null !== $request->request->get('cboReunion')){
                $agenda->setReunion($reunionRepository->find($request->request->get('cboReunion')));
            }
            $entityManager = $this->getDoctrine()->getManager();
           

            $observacion=new AgendaObservacion();
            $observacion->setAgenda($agenda);
            $observacion->setUsuarioRegistro($usuarioRepository->find($user->getId()));
            $observacion->setStatus($agendaStatusRepository->find($request->request->get('chkStatus')));
            $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
            $observacion->setObservacion($request->request->get('txtObservacion'));
           
            $entityManager->persist($observacion);
            $entityManager->flush();
            $entityManager->persist($agenda);
            $entityManager->flush();
            return $this->redirectToRoute('panel_abogado_index');
        }
        return $this->render('panel_abogado/new.html.twig', [
            'agenda'=>$agenda,
            'pagina'=>$pagina->getNombre().' | Gestionar',
            
            'statues'=>$agendaStatusRepository->findBy(['perfil'=>[$agenda->getAbogado()->getUsuarioTipo()->getId(),0]],['orden'=>'asc']),
        ]);

    }
    /**
     * @Route("/{id}/del_rol", name="panel_abogado_del_rol",  methods={"DELETE"})
     */
    public function delRol(ContratoRol $contratoRol,Request $request,JuzgadoRepository $juzgadoRepository,ContratoRolRepository $contratoRolRepository): Response
    {
        
        $user=$this->getUser();

        
        $abogado=$this->getDoctrine()->getRepository(Usuario::class)->find($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contratoRol);
            $entityManager->flush();

        
        return $this->render('panel_abogado/contratoRoles.html.twig', [
            'contrato_rols' => $contratoRolRepository->findByTemporal($abogado->getId()),
           
        ]);
    }
    /**
     * @Route("/{id}/contrata", name="panel_abogado_contrata", methods={"GET","POST"})
     */
    public function contrata(Agenda $agenda,Request $request,
                            AgendaStatusRepository  $agendaStatusRepository,
                            JuzgadoRepository $juzgadoRepository,
                            ContratoRolRepository $contratoRolRepository,
                            SucursalRepository $sucursalRepository,
                            DiasPagoRepository $diasPagoRepository,
                            UsuarioRepository $usuarioRepository,
                            UserPasswordEncoderInterface $encoder,
                            usuarioTipoRepository $usuarioTipoRepository,
                            ContratoRepository $contratoRepository
                            ):Response
    {
        $this->denyAccessUnlessGranted('create','panel_abogado');

        $user=$this->getUser();
        $juzgados=$juzgadoRepository->findAll();
        if(null != $request->request->get('chkStatus')){
            $agenda->setStatus($agendaStatusRepository->find($request->request->get('chkStatus')));
            if(null !== $request->request->get('cboAbogado')){
                $agenda->setAbogado($usuarioRepository->find($request->request->get('cboAbogado')));
            }
            if(null !== $request->request->get('txtCiudad')){
                $agenda->setCiudadCliente($request->request->get('txtCiudad'));
            }
            if(null !== $request->request->get('txtFechaAgendamiento')){
                $agenda->setFechaAsignado(new \DateTime($request->request->get('txtFechaAgendamiento')." ".$request->request->get('cboHoras').":00"));
            }
            if(null !== $request->request->get('txtMonto')){
                $agenda->setMonto($request->request->get('txtMonto'));
            }
            if(null !== $request->request->get('cboReunion')){
                $agenda->setReunion($reunionRepository->find($request->request->get('cboReunion')));
            }
            $entityManager = $this->getDoctrine()->getManager();
           

            $observacion=new AgendaObservacion();
            $observacion->setAgenda($agenda);
            $observacion->setUsuarioRegistro($usuarioRepository->find($user->getId()));
            $observacion->setStatus($agendaStatusRepository->find($request->request->get('chkStatus')));
            $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
            $observacion->setObservacion($request->request->get('txtObservacion'));
           
            $entityManager->persist($observacion);
            $entityManager->flush();
            $entityManager->persist($agenda);
            $entityManager->flush();
            return $this->redirectToRoute('panel_abogado_index');
        }

        $contrato=$contratoRepository->findOneBy(['agenda'=>$agenda->getId()]);
        if(null == $contrato){
            
            $contrato=new Contrato();
            $contrato->setAgenda($agenda);
            $contrato->setNombre($agenda->getNombreCliente());
            $contrato->setTelefono($agenda->getTelefonoCliente());
            $contrato->setEmail($agenda->getEmailCliente());
            $contrato->setRut($agenda->getRutCliente());
            $contrato->setTelefonoRecado($agenda->getTelefonoRecadoCliente());
            $contrato->setReunion($agenda->getReunion());

            if(is_null($agenda->getCiudadCliente())){
                $contrato->setCiudad(' ');
            }else{
                $contrato->setCiudad($agenda->getCiudadCliente());
            }
            $contrato->setCuotas(1);
            $contrato->setMontoNivelDeuda($agenda->getMonto()); 
        }
        //$contrato->setCiudad($agenda->getCiudadCliente());
        $form = $this->createForm(ContratoType::class, $contrato, [
            'action' =>$this->generateUrl('panel_abogado_contrata',['id'=>$agenda->getId()])
        ]);
    
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //$this->getDoctrine()->getManager()->flush();
            $agenda->setStatus($agendaStatusRepository->find('7'));
            $contrato->setDiaPago($request->request->get('chkDiasPago'));
            $contrato->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
            $contrato->setSucursal($sucursalRepository->find($request->request->get('cboSucursal')));
            $contrato->setTramitador($usuarioRepository->find($request->request->get('cboTramitador')));
            $contrato->setFechaPrimerPago(new \DateTime(date($request->request->get('txtFechaPago')."-1 00:00:00")));
            $entityManager = $this->getDoctrine()->getManager();

            $agenda->setNombreCliente($contrato->getNombre());
            $agenda->setTelefonoCliente($contrato->getTelefono());
            $agenda->setEmailCliente($contrato->getEmail());

            $entityManager->persist($contrato);
            $entityManager->flush();
            $entityManager->persist($agenda);
            $entityManager->flush();
           
            $contratoRoles=$contratoRolRepository->findByTemporal($user->getId());
            foreach($contratoRoles as $contratoRol){
                $contratoRol->setContrato($contrato);
                $entityManager->persist($contratoRol);
                $entityManager->flush();
            }

            

            return $this->redirectToRoute('contrato_finalizar',['id'=>$contrato->getId()]);
        }
        return $this->render('panel_abogado/contrata.html.twig',[
            'agenda'=>$agenda,
            'contrato'=>$contrato,
            'juzgados'=>$juzgados,
            'tramitadores'=>$usuarioRepository->findByCuenta($agenda->getCuenta()->getId(),['usuarioTipo'=>7]),
            'form'=>$form->createView(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'sucursales'=>$sucursalRepository->findBy(['cuenta'=>$agenda->getCuenta()->getId()]),
        ] );
    }
    /**
     * @Route("/{id}/no_contrata", name="panel_abogado_no_contrata", methods={"GET","POST"})
     */
    public function noContrata(Agenda $agenda,Request $request,
                    AgendaStatusRepository  $agendaStatusRepository,
                    JuzgadoRepository $juzgadoRepository,
                    ContratoRolRepository $contratoRolRepository,
                    UsuarioRepository $usuarioRepository,
                    SucursalRepository $sucursalRepository): Response
    {
        $this->denyAccessUnlessGranted('create','panel_abogado');

        $user=$this->getUser();
        
        if(null !== $request->request->get('status')){
            //$agenda->setStatus($agendaStatusRepository->find($request->request->get('status')));
        }
        if(null !==$request->request->get('hdNoContrata')){
            $agenda->setStatus($agendaStatusRepository->find($request->request->get('hdNoContrata')));
           // $agenda->setObservacion($agenda->getObservacion()."<hr>".$request->request->get('txtObservacion'));
            $entityManager = $this->getDoctrine()->getManager();
            $observacion=new AgendaObservacion();
            $observacion->setAgenda($agenda);
            $observacion->setUsuarioRegistro($usuarioRepository->find($user->getId()));
            $observacion->setStatus($agendaStatusRepository->find($request->request->get('hdNoContrata')));
            $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
            $observacion->setObservacion($request->request->get('txtObservacion'));
           // $agenda->setObservacion("");
            $entityManager->persist($observacion);
            $entityManager->flush();


            
            $entityManager->persist($agenda);
            $entityManager->flush();
            return $this->redirectToRoute('panel_abogado_index');
        }

        return $this->render('panel_abogado/no_contrata.html.twig', [
            'agenda'=>$agenda,
            'status'=>$_GET['status']
        ]);
    }
    

}
