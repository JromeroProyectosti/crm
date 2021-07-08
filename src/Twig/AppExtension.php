<?php

namespace App\Twig;
use App\Entity\Vencimiento;
use App\Entity\Pago;
use App\Entity\Cuota;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends AbstractExtension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('special_chars', [$this, 'decode_utf8']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('special_chars_func', [$this, 'decode_utf8']),
            new TwigFunction('suma_mes',[$this,'suma_mes']),
            new TwigFunction('semaforo',[$this,'semaforo']),
            new TwigFunction('ultimoPago',[$this,'ultimoPago']),
            new TwigFunction('montoDeuda',[$this,'montoDeuda']),
        ];
    }

    public function decode_utf8($value)
    {
       
        return  htmlentities($value);
    }
    public function suma_mes($dia,$mes,$anio,$suma){


        $ts = mktime(0, 0, 0, $mes + $suma, 1,$anio);

        if(date("n",$ts)==2){
            if($dia==30){
                $dia=date("d",mktime(0,0,0,$mes+$suma+1,1,$anio)-24);
            }
        }
        
        return date("d-m-Y", mktime(0,0,0,$mes+$suma,$dia,$anio));
    }

    public function semaforo($fecha){

        $color="";
        $icono="";
        $inicio=strtotime($fecha);
        $fin=strtotime(date("Y-m-d"));
        $dif=($fin-$inicio);

        if($dif<0){
            $dif=0;
        }else{

            $dif = round($dif/60/60/24);
        }
        
        $em=$this->container->get('doctrine');
        $vencimientos=$em->getRepository(Vencimiento::class)->findAll();

        
        foreach($vencimientos as $vencimiento){
            $statusMin=false;
            $statusMax=false;

            if(null != $vencimiento->getValMin()){
                if($vencimiento->getValMin() <= $dif ){
                    $statusMin=true;
                }
            }else{
                $statusMin=true;
            }
            if(null != $vencimiento->getValMax()){
                if($vencimiento->getValMax()>=$dif){
                    $statusMax=true;
                }
            }else{
                $statusMax=true;
            }
            if($statusMax && $statusMin){
                $color = $vencimiento->getColor();
                $icono= $vencimiento->getIcono();
            }
        }
    
        
        return "<p class='$color' ><i class='$icono' ></i></p>";
    }
    public function ultimoPago($contrato){
        $em=$this->container->get('doctrine');
        $pago=$em->getRepository(Pago::class)->findUPByContrato($contrato);
        $ultimoPago=false;
        if($pago){
            $ultimoPago=$pago->getFechaPago()->format('Y-m-d')." ".$pago->getHoraPago()->format('H:i');
        }
        return $ultimoPago;
    }
    public function montoDeuda($contrato){
        $em=$this->container->get('doctrine');
        $vencimiento=$em->getRepository(Vencimiento::class)->findAll();

        $otros=' DATEDIFF(now(),c.fechaPago)>'.$vencimiento[0]->getValMax();
        $cuota=$em->getRepository(Cuota::class)->deudaTotal($contrato,$otros);
       
        if(count($cuota)>0){
            return $cuota[0][1]-$cuota[0][2];
        }else{
            return 0;
        }
        
        
    }
}
