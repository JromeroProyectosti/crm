<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContratoRepository;

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
        $contratos=$contratoRepository->findAll();
        return $this->render('reporte/reporte_agendador.html.twig', [
            'controller_name' => 'ReporteController',
            'contratos' => $contratos,
        ]);
    }
        /**
     * @Route("/abogado", name="reporte_abogado", methods={"GET"})
     */
    public function abogado(): Response
    {
        return $this->render('reporte/reporte_abogado.html.twig', [
            'controller_name' => 'ReporteController',
        ]);
    }
}
