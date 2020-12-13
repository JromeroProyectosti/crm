<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
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
}
