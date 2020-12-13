<?php

namespace App\Controller;
use App\Repository\ConfiguracionRepository;
use App\Entity\ClientePotencial;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WebhookRepository;
/**
     * @Route("/webhook")
     */
class WebhookController extends AbstractController
{
    /**
     * @Route("/", name="webhook_index", methods={"GET","POST","PUT"})
     */
    public function index(ConfiguracionRepository $configuracionRepository)
    {

    	$challenge = $_REQUEST['hub_challenge'];
		$verify_token = $_REQUEST['hub_verify_token'];
        $configuracion=$configuracionRepository->find(1);

		if ($verify_token === $configuracion->getVerifyToken()) {
         echo $challenge;
            
		}

		
        $access_token=$configuracion->getAccessToken();

        $input = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($input,true));



        $lead=$input['entry'][0]['changes'][0]['value'];
        
        $clientePotencial=new ClientePotencial();
        $data = json_decode( file_get_contents('https://graph.facebook.com/'.$lead['leadgen_id'].'?access_token='.$access_token), true );

        $clientePotencial->setFormId($lead['form_id']);
        $clientePotencial->setLeadgenId($lead['leadgen_id']);
        $clientePotencial->setPageId($lead['page_id']);
        $clientePotencial->setCreateTime($lead['created_time']);
        $clientePotencial->setCampos($data);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($clientePotencial);
        $entityManager->flush();

        return $this->render('webhook/index.html.twig', [
                'controller_name' => 'WebhookController',
                'challenge'=>$challenge,
            ]);
    }
}
