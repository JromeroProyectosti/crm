<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Usuario;
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
 * @Route("/nocontesta")
 */
class NocontestaController extends AbstractController
{
    /**
     * @Route("/", name="nocontesta_index")
     */
    public function index(AgendaRepository $agendaRepository,CuentaRepository $cuentaRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request): Response
    {
        $this->denyAccessUnlessGranted('view','nocontesta');

        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('nocontesta',$user->getEmpresaActual());
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
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,'10',$filtro);
            break;
            case 1:
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,'10',$filtro);
            break;
            case 5:
                $query=$agendaRepository->findByPers($user->getId(),null,$compania,'10',$filtro);
            break;
            default:
                $query=$agendaRepository->findByPers($user->getId(),null,$compania,'10',$filtro);
            break;
        }

        $companias=$cuentaRepository->findByPers($user->getId());
        
        
        $agendas=$paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        20 /*limit per page*/,
        array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));

        return $this->render('nocontesta/index.html.twig', [
            'agendas' => $agendas,
            'pagina'=>$pagina->getNombre(),
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania
        ]);
        
    }
    /**
     * @Route("/{id}", name="nocontesta_show", methods={"GET","POST"})
     */
    public function show(Agenda $agenda,
            AgendaRepository $agendaRepository,
            AgendaStatusRepository $agendaStatusRepository,
            CuentaRepository $cuentaRepository,
            UsuarioRepository $usuarioRepository,
            ReunionRepository $reunionRepository
            ,ModuloPerRepository $moduloPerRepository,
            Request $request): Response
        {
        $this->denyAccessUnlessGranted('view','nocontesta');

        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('nocontesta',$user->getEmpresaActual());
        $form = $this->createForm(AgendaType::class, $agenda);
        switch($user->getUsuarioTipo()->getId()){
            case 1:
                $cuentas=$cuentaRepository->findBy(['empresa'=>$user->getEmpresaActual()]);
            break;
            default:
                $cuentas=$cuentaRepository->findByPers($usuarioRepository->find($user->getId()));
        }
        $form->handleRequest($request);

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
            return $this->redirectToRoute('nocontesta_index');
        }
        return $this->render('nocontesta/new.html.twig', [
        'agenda'=>$agenda,
        'form' => $form->createView(),
        'pagina'=>$pagina->getNombre(),
        'cuentas'=>$cuentas,
        ]);
    }
}
