<?php

namespace App\Controller;
use App\Entity\Contrato;
use App\Entity\ContratoRol;
use App\Entity\Usuario;
use App\Entity\Cuota;
use App\Form\ContratoType;
use App\Entity\AgendaObservacion;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
 /**
 * @Route("/terminos")
 */
class TerminosController extends AbstractController
{
    /**
     * @Route("/", name="terminos_index")
     */
    public function index(ContratoRepository $contratoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        //$this->denyAccessUnlessGranted('view','contrato');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('terminos',$user->getEmpresaActual());
        $filtro=null;
        $error='';
        $error_toast="";
        $fecha="a.status = 15 ";
        if(null !== $request->query->get('error_toast')){
            $error_toast=$request->query->get('error_toast');
        }
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
            $fecha.="and c.fechaCreacion between '$dateInicio' and '$dateFin 23:59:59' " ;
        }else{
            $dateInicio=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-60*60*24*30);
            $dateFin=date('Y-m-d');
        }
        
    
        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 4:
            case 1:
                $query=$contratoRepository->findByPers(null,$user->getEmpresaActual(),$compania,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
            break;
            case 7:
                $query=$contratoRepository->findByPers(null,null,$compania,$filtro,null,$fecha." and c.tramitador = ".$user->getId());
                $companias=$cuentaRepository->findByPers($user->getId());
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
        return $this->render('terminos/index.html.twig', [
            'contratos' => $contratos,
            'bFiltro'=>$filtro,
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
    * @Route("/{id}/edit", name="terminos_edit", methods={"GET","POST"})
    */
    public function edit(Contrato $contrato,
    DiasPagoRepository $diasPagoRepository,
    ModuloPerRepository $moduloPerRepository,
    AgendaStatusRepository $agendaStatusRepository,
    UsuarioRepository $usuarioRepository,
    Request $request): Response
    {
        $this->denyAccessUnlessGranted('edit','terminos');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('terminos',$user->getEmpresaActual());

        if(null !== $request->query->get('status')){
            $status= $request->query->get('status');
            $observacion_texto= $request->request->get('txtObservacion');
            $entityManager = $this->getDoctrine()->getManager();
            $agenda=$contrato->getAgenda();
            $agenda->setStatus($agendaStatusRepository->find($status));

            $entityManager->persist($agenda);
            $entityManager->flush();

            $observacion=new AgendaObservacion();
            $observacion->setAgenda($agenda);
            $observacion->setUsuarioRegistro($usuarioRepository->find($user->getId()));
            $observacion->setStatus($agendaStatusRepository->find($status));
            $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
            $observacion->setObservacion($observacion_texto);
            $entityManager->persist($observacion);
            $entityManager->flush();
            $error_toast="Toast.fire({
                icon: 'success',
                title: 'Cliente confirma termino de contrato'
              })";
            return $this->redirectToRoute('terminos_index',['error_toast'=>$error_toast]);

        }
        return $this->render('terminos/show.html.twig', [
            'contrato' => $contrato,
            'agenda'=>$contrato->getAgenda(),
            'pagina'=>$pagina->getNombre(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            'metodo'=>'R',
            
        ]);
    }
    /**
     * @Route("/{id}/pdf", name="terminos_pdf", methods={"GET","POST"})
     */
    public function pdf(Contrato $contrato,\Knp\Snappy\Pdf $snappy): Response
    {
        $this->denyAccessUnlessGranted('view','terminos');
        $filename = sprintf('desestimiento-'.$contrato->getId().'-%s.pdf',rand(0,9000));
       
        $html = $this->renderView('terminos/print.html.twig', array(
            'contrato' => $contrato,
            'Titulo'=>"Contrato"
        ));

        $entityManager = $this->getDoctrine()->getManager();
        $contrato->setPdfTermino($filename);
        $entityManager->persist($contrato);
        $entityManager->flush();

        $snappy->generateFromHtml(
           $html,
           $this->getParameter('url_root'). $this->getParameter('pdf_contratos').$filename
        );
        return new PdfResponse(
            $snappy->getOutputFromHtml($html, array(
                'page-size' => 'letter')),
            $filename
        );
    }
    /**
    * @Route("/{id}", name="terminos_show", methods={"GET"})
    */
    public function show(Contrato $contrato,DiasPagoRepository $diasPagoRepository,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','terminos');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('terminos',$user->getEmpresaActual());
        return $this->render('terminos/show.html.twig', [
            'contrato' => $contrato,
            'agenda'=>$contrato->getAgenda(),
            'pagina'=>$pagina->getNombre(),
            'diasPagos'=>$diasPagoRepository->findAll(),
            
        ]);
    }
}
