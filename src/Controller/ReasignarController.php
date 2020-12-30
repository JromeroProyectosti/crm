<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Usuario;
use App\Entity\AgendaStatus;
use App\Entity\AgendaObservacion;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use App\Repository\ReunionRepository;
use App\Repository\UsuarioRepository;
use App\Repository\AgendaStatusRepository;
use App\Repository\CuentaRepository;
use App\Repository\ModuloRepository;
use App\Repository\ModuloPerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reasignar")
 */
class ReasignarController extends AbstractController
{
    /**
     * @Route("/", name="reasignar_index")
     */
    public function index(AgendaRepository $agendaRepository,CuentaRepository $cuentaRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request): Response
    {
        $this->denyAccessUnlessGranted('view','reasignar');
        

        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('reasignar',$user->getEmpresaActual());
        $filtro=null;
        $compania=null;
        if(null !== $request->request->get('bFiltro')){
            $filtro=$request->request->get('bFiltro');
        }
        if(null !== $request->request->get('bCompania')){
            $compania=$request->request->get('bCompania');
        }


        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 4:
            case 1:
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,'9',$filtro,3);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
            break;
            case 5:
                $query=$agendaRepository->findByPers($user->getId(),null,$compania,'9',$filtro,3);
                $companias=$cuentaRepository->findByPers($user->getId());
            break;
            default:
                $query=$agendaRepository->findByPers($user->getId(),null,$compania,'9',$filtro,3);
                $companias=$cuentaRepository->findByPers($user->getId());
            break;
        }

        
        
        
        $agendas=$paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        20 /*limit per page*/,
        array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));

        return $this->render('reasignar/index.html.twig', [
            'agendas' => $agendas,
            'pagina'=>$pagina->getNombre(),
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania
        ]);
        
    }
    /**
     * @Route("/{id}", name="reasignar_show", methods={"GET","POST"})
     */
    public function show(Agenda $agenda,
            AgendaRepository $agendaRepository,
            AgendaStatusRepository $agendaStatusRepository,
            CuentaRepository $cuentaRepository,
            UsuarioRepository $usuarioRepository,
            ReunionRepository $reunionRepository,
            ModuloPerRepository $moduloPerRepository,
            Request $request): Response
        {
        $this->denyAccessUnlessGranted('view','reasignar');
        
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('reasignar',$user->getEmpresaActual());
        $form = $this->createForm(AgendaType::class, $agenda);

        $form->handleRequest($request);
        switch($user->getUsuarioTipo()->getId()){
            case 1:
                $cuentas=$cuentaRepository->findBy(['empresa'=>$user->getEmpresaActual()]);
            break;
            default:
                $cuentas=$cuentaRepository->findByPers($usuarioRepository->find($user->getId()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
        
        $agenda->setStatus($agendaStatusRepository->find(11));
        $cuenta=$request->request->get('cboCuenta');
        $usuario=$request->request->get('cboAgendador');
        $agenda->setCuenta($cuentaRepository->find($cuenta));
        $agenda->setAgendador($usuarioRepository->find($usuario));
        $entityManager = $this->getDoctrine()->getManager();


        $observacion=new AgendaObservacion();
        $observacion->setAgenda($agenda);
        $observacion->setUsuarioRegistro($usuarioRepository->find($user->getId()));
        $observacion->setStatus($agendaStatusRepository->find(11));
        $observacion->setFechaRegistro(new \DateTime(date("Y-m-d H:i:s")));
        $observacion->setObservacion($request->request->get('txtObservacion'));

        $entityManager->persist($observacion);
        $entityManager->flush();
        $entityManager->persist($agenda);
        $entityManager->flush();
        return $this->redirectToRoute('reasignar_index');
        }
        return $this->render('reasignar/new.html.twig', [
        'agenda'=>$agenda,
        'form' => $form->createView(),
        'cuentas'=>$cuentas,
        'pagina'=>$pagina->getNombre(),
        
        ]);
    }
}
