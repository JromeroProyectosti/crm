<?php

namespace App\Controller;
use App\Repository\AgendaRepository;
use App\Repository\CuentaRepository;
use App\Repository\ModuloPerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContratoRepository;

use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/reporte")
 */

class ReporteController extends AbstractController
{
    /**
     * @Route("/", name="reporte_index")
     */
    public function index(): Response
    {
        return $this->render('reporte/index.html.twig', [
            'controller_name' => 'ReporteController',
        ]);
    }
    /**
     * @Route("/agendador", name="reporte_agendador")
     */
    public function agendador(ContratoRepository $contratoRepository): Response
    {
        $this->denyAccessUnlessGranted('view','reporte_agendador');
        $contratos=$contratoRepository->findAll();
        return $this->render('reporte/reporte_agendador.html.twig', [
            'controller_name' => 'ReporteController',
            'contratos' => $contratos,
        ]);
    }
     /**
     * @Route("/abogado", name="reporte_abogado", methods={"GET"})
     */
    public function abogado(AgendaRepository $agendaRepository,
                            CuentaRepository $cuentaRepository,
                            PaginatorInterface $paginator,
                            Request $request,
                            ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','reporte_abogado');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('panel_abogado',$user->getEmpresaActual());

        $filtro=null;
        $compania=null;
        $fecha=null;
        $statues='5';
        $statuesgroup='4,5,7,6,8,14,15';
        $status=null;
        $tipo_fecha=1;
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
        if(null !== $request->query->get('bTipofecha') ){
            $tipo_fecha=$request->query->get('bTipofecha');
        }
        switch($tipo_fecha){
            case 0:
                $fecha="a.fechaCarga between '$dateInicio' and '$dateFin 23:59:59'" ;
                break;
            case 1:
                $fecha="a.fechaAsignado between '$dateInicio' and '$dateFin 23:59:59'" ;
                break;
            case 2:
                $fecha="a.fechaContrato between '$dateInicio' and '$dateFin 23:59:59'" ;
                break;
            default:
                $fecha="a.fechaCarga between '$dateInicio' and '$dateFin 23:59:59'" ;
                break;
        }
       // $fecha="a.fechaAsignado between '$dateInicio' and '$dateFin 23:59:59'" ;
        
        
        //$queryresumen=$agendaRepository->findByAgendGroup(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,null,$fecha);   
        switch($user->getUsuarioTipo()->getId()){
            case 3:
            case 1:
            case 4:
                $query=$agendaRepository->findByAgendReporte(null,$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,1,$fecha);   
                $companias=$cuentaRepository->findByPers(null,$user->getEmpresaActual());
            break;
            default:
                $query=$agendaRepository->findByAgendReporte($user->getId(),$user->getEmpresaActual(),$compania,$statuesgroup,$filtro,1,$fecha);   
                $companias=$cuentaRepository->findByPers($user->getId());
            break;
        }
        $datos=array();
        foreach($query as $total){
            $cantAgendado=0;
            $cantNoCalifica=0;
            $cantNoContrata=0;
            $cantContrata=0;
            $cantRatificaTermino=0;
            $agenda=$total[0];
            //$valor=$agenda.valor;

            $agendados=$agendaRepository->findByAgendReporte($agenda->getAbogado()->getId(),$user->getEmpresaActual(),$compania,'5',$filtro,1,$fecha);
            foreach($agendados as $agendado){
                $cantAgendado=$agendado['valor'];
            }
            $nocalifican=$agendaRepository->findByAgendReporte($agenda->getAbogado()->getId(),$user->getEmpresaActual(),$compania,'6',$filtro,1,$fecha);
            foreach($nocalifican as $nocalifica){
                $cantNoCalifica=$nocalifica['valor'];
            }
            $nocontratan=$agendaRepository->findByAgendReporte($agenda->getAbogado()->getId(),$user->getEmpresaActual(),$compania,'8',$filtro,1,$fecha);
            foreach($nocontratan as $nocontrata){
                $cantNoContrata=$nocontrata['valor'];
            }
            $contratan=$agendaRepository->findByAgendReporte($agenda->getAbogado()->getId(),$user->getEmpresaActual(),$compania,'7',$filtro,1,$fecha);
            foreach($contratan as $contrata){
                $cantContrata=$contrata['valor'];
            }
            $ratificantermino=$agendaRepository->findByAgendReporte($agenda->getAbogado()->getId(),$user->getEmpresaActual(),$compania,'15',$filtro,1,$fecha);
            foreach($ratificantermino as $ratificatermino){
                $cantRatificaTermino=$ratificatermino['valor'];
            }
            $datos[]=array(
                "abogado_id"=>$agenda->getAbogado()->getId(),
                "abogado_nombre"=>$agenda->getAbogado()->getNombre(),
                "total"=>$total['valor'],
                "agendado"=>$cantAgendado,
                "nocalifica"=>$cantNoCalifica,
                "nocontrata"=>$cantNoContrata,
                "contrata"=>$cantContrata,
                "ratificatermino"=>$cantRatificaTermino,
                'monto'=>$total['monto']
            );

        }
        
        return $this->render('reporte/reporte_abogado.html.twig', [
            'controller_name' => 'ReporteController',
            'pagina'=>$pagina->getNombre(),
            'reportes'=>$datos,
            'bFiltro'=>$filtro,
            'companias'=>$companias,
            'bCompania'=>$compania,
            'dateInicio'=>$dateInicio,
            'dateFin'=>$dateFin,
            'tipoFecha'=>$tipo_fecha,

        ]);
    }

    /**
     * @Route("/cobrador", name="reporte_cobrador", methods={"GET"})
     */
    public function cobrador(): Response
    {
        $this->denyAccessUnlessGranted('view','reporte_cobrador');
        return $this->render('reporte/reporte_cobrador.html.twig', [
            'controller_name' => 'ReporteController',
        ]);
    }
}
