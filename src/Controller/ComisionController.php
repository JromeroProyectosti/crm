<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContratoRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CuentaRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ModuloPerRepository;
use App\Repository\AgendaRepository;

/**
 * @Route("/comision")
 */

class ComisionController extends AbstractController
{
    /**
     * @Route("/", name="comision_index", methods={"GET","POST"})
     */
    public function index(): Response
    {
        return $this->render('comision/index.html.twig', [
            'controller_name' => 'ComisionController',
        ]);
        
    }
    /**
     * @Route("/agendador", name="comision_agendador", methods={"GET","POST"})
     */
    public function agendador(AgendaRepository $agendaRepository,ContratoRepository $contratoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        $filtro=null;
        $compania=null;
        $statuesgroup='7,14';
        $status=null;

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
        $fecha="a.fechaContrato between '$dateInicio' and '$dateFin 23:59:59'" ;

        if(null !== $request->query->get('bStatus') && trim($request->query->get('bStatus')!='')){
            $status=$request->query->get('bStatus');
            $statues=$status;
            $statuesgroup=$status;
        }

        switch($user->getUsuarioTipo()->getId()){
            case 1:
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                $queryresumen=$agendaRepository->findByPersGroup(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,0,$fecha);
            break;
        }

        //$contratos=$contratoRepository->findAll();
        $agendas=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));
        return $this->render('comision/comision_agendador.html.twig', [
            //'controller_name' => 'ComisionController',
            //'contratos' => $query,
            'agendas' => $agendas,
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'resumenes'=>$queryresumen,
            'status'=>$statuesgroup,
        ]);
    }
    /**
     * @Route("/abogado", name="comision_abogado", methods={"GET","POST"})
     */
    public function abogado(AgendaRepository $agendaRepository,ContratoRepository $contratoRepository,PaginatorInterface $paginator,ModuloPerRepository $moduloPerRepository,Request $request,CuentaRepository $cuentaRepository): Response
    {
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('contrato',$user->getEmpresaActual());
        $filtro=null;
        $compania=null;
        $statuesgroup='7,14';
        $status=null;

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
        $fecha="a.fechaContrato between '$dateInicio' and '$dateFin 23:59:59'" ;

        switch($user->getUsuarioTipo()->getId()){
            case 1:
                $query=$agendaRepository->findByPers(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,null,$fecha);
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
                $queryresumen=$agendaRepository->findByPersGroup(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,0,$fecha);
            break;
        }

        //$contratos=$contratoRepository->findAll();
        $agendas=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/,
            array('defaultSortFieldName' => 'id', 'defaultSortDirection' => 'desc'));
        return $this->render('comision/comision_agendador.html.twig', [
            //'controller_name' => 'ComisionController',
            //'contratos' => $query,
            'agendas' => $agendas,
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'pagina'=>$pagina->getNombre(),
            'resumenes'=>$queryresumen,
            'status'=>$status,
        ]);
    }
}

