<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\ContratoRol;
use App\Entity\Usuario;
use App\Form\ContratoType;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

        $compania=null;
        if(null !== $request->request->get('bFiltro') && $request->request->get('bFiltro')!=''){
            $filtro=$request->request->get('bFiltro');
        }
        if(null !== $request->request->get('bCompania') && $request->query->get('bCompania')!=0){
            $compania=$request->request->get('bCompania');
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
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
        ]);
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
     * @Route("/{id}", name="contrato_show", methods={"GET"})
     */
    public function show(Contrato $contrato,DiasPagoRepository $diasPagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        return $this->render('contrato/show.html.twig', [
            'contrato' => $contrato,
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
                    ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('edit','contrato');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        $juzgados=$juzgadoRepository->findAll();
        $form = $this->createForm(ContratoType::class, $contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $contrato->setSucursal($sucursalRepository->find($request->request->get('cboSucursal')));
            $contrato->setDiaPago($request->request->get('chkDiasPago'));
            $contrato->setTramitador($usuarioRepository->find($request->request->get('cboTramitador')));
            $contrato->setFechaPrimerPago(new \DateTime(date($request->request->get('txtFechaPago')."-1 00:00:00")));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contrato);
            $entityManager->flush();

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('contrato/edit.html.twig', [
            'contrato' => $contrato,
            'form' => $form->createView(),
            'juzgados'=>$juzgados,
            'pagina'=>$pagina->getNombre(),
            'tramitadores'=>$usuarioRepository->findByCuenta($contrato->getAgenda()->getCuenta()->getId(),['usuarioTipo'=>7]),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'sucursales'=>$sucursalRepository->findBy(['cuenta'=>$contrato->getAgenda()->getCuenta()->getId()]),
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
                            UsuarioTipoRepository $usuarioTipoRepository
                            ): Response
    {
        $this->denyAccessUnlessGranted('create','panel_abogado');
        $juzgados=$juzgadoRepository->findAll();
        $form = $this->createForm(ContratoType::class, $contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $contrato->setDiaPago($request->request->get('chkDiasPago'));
            $contrato->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
            $contrato->setSucursal($sucursalRepository->find($request->request->get('cboSucursal')));
            $contrato->setTramitador($usuarioRepository->find($request->request->get('cboTramitador')));
            
            $entityManager = $this->getDoctrine()->getManager();
            $agenda=$contrato->getAgenda();

            $usuario=$usuarioRepository->findOneBy(['username'=>$agenda->getEmailCliente()]);
            if(!$usuario){
                $usuario=new Usuario();
                $usuario->setUsername($agenda->getEmailCliente());
                $password=$usuario->getPassword();
                $encoded=$encoder->encodePassword($usuario,$password);
                $usuario->setPassword($encoded);
                $usuario->setCorreo($agenda->getEmailCliente());
                $usuario->setNombre($agenda->getNombreCliente());
                $usuario->setTelefono($agenda->getTelefonoCliente());
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

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('contrato/finalizar.html.twig', [
            'contrato' => $contrato,
            'form' => $form->createView(),
            'juzgados'=>$juzgados,
            'pagina'=>"Revise los datos para finalizar",
            'tramitadores'=>$usuarioRepository->findByCuenta($contrato->getAgenda()->getCuenta()->getId(),['usuarioTipo'=>7]),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'sucursales'=>$sucursalRepository->findBy(['cuenta'=>$contrato->getAgenda()->getCuenta()->getId()]),
        ]);
    }

    

    /**
     * @Route("/{id}/pdf", name="contrato_pdf", methods={"GET","POST"})
     */
    public function pdf(Contrato $contrato,\Knp\Snappy\Pdf $snappy): Response
    {
        $this->denyAccessUnlessGranted('view','contrato');
        $filename = sprintf('Contrato-'.$contrato->getId().'-%s.pdf', date('Y-m-d-hh-ss'));
       
        $html = $this->renderView('contrato/print.html.twig', array(
            'contrato' => $contrato,
            
            'Titulo'=>"Contrato"
        ));
        return new PdfResponse(
            $snappy->getOutputFromHtml($html, array(
                'page-size' => 'letter')),
            $filename
        );
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
}
