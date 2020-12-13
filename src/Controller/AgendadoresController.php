<?php

namespace App\Controller;
use App\Entity\Usuario;
use App\Entity\UsuarioCategoria;
use App\Entity\Empresa;
use App\Entity\UsuarioCuenta;
use App\Entity\Cuenta;
use App\Entity\UsuarioStatus;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use App\Repository\UsuarioTipoRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\UsuarioTipoDocumentoRepository;
use App\Repository\UsuarioNoDisponibleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/agendadores")
 */
class AgendadoresController extends AbstractController
{
    /**
     * @Route("/", name="agendadores_index",methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository,
                    ModuloPerRepository $moduloPerRepository,
                    PaginatorInterface $paginator,
                    Request $request): Response
    {
        $this->denyAccessUnlessGranted('view','agendadores');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('agendadores',$user->getEmpresaActual());
        $query=$usuarioRepository->findBy(['usuarioTipo'=>5,'estado'=>1]);
        $usuarios=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/,
            array('defaultSortFieldName' => 'nombre', 'defaultSortDirection' => 'asc'));

        return $this->render('agendadores/index.html.twig', [
            'usuarios' => $usuarios,
            'pagina'=>$pagina->getNombre(),
        ]);
    }

    /**
     * @Route("/new", name="agendadores_new", methods={"GET","POST"})
     */
    public function new(Request $request,
                        UserPasswordEncoderInterface $encoder,
                        UsuarioTipoRepository $usuarioTipoRepository,
                        ModuloPerRepository $moduloPerRepository,
                        UsuarioTipoDocumentoRepository $tipoDocumento): Response
    {
        $this->denyAccessUnlessGranted('create','agendadores');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('agendadores',$user->getEmpresaActual());
        $usuario = new Usuario();
        $usuario->setEstado(1);
        $empresa=$this->getDoctrine()->getRepository(Empresa::class)->find($user->getEmpresaActual());
        $statues=$this->getDoctrine()->getRepository(UsuarioStatus::class)->findBy(['id'=>[1,2]]);
        $usuarioCategorias=$empresa->getUsuarioCategorias();
        $cuentas=$empresa->getCuentas();
        $choices= array();
        foreach($usuarioCategorias as $usuarioCategoria){
            $choices[$usuarioCategoria->getNombre()]=$usuarioCategoria->getId();
        }
        $statusChoices=array();
        foreach($statues as $status){
            $statusChoices[$status->getNombre()]=$status->getId();
        }
        
        $usuario->setUsuarioTipo($usuarioTipoRepository->find(5));
        $usuario->setFechaActivacion(new \DateTime(date('Y-m-d H:i:s')));
        
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->add('whatsapp',TextType::class);
        

        $form->add('sexo',ChoiceType::class,[
            'choices' =>[
                'Masculino'=>'Masculino',
                'Femenino'=>'Femenino'
            ],
        ]
        );
        $form->add("password", TextType::class);
        $form->add("passwordAnt", TextType::class,[
            'required'   => false,
            'attr'=>[
                'style'=>'display:none'
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $password=$usuario->getPassword();
            $encoded=$encoder->encodePassword($usuario,$password);
            $usuario->setPassword($encoded);

           
            $usuarioCuenta=new UsuarioCuenta();
            $usuario->setTipoDocumento($tipoDocumento->find($request->request->get('cboTipoDocumento')));

            $categoria=$this->getDoctrine()->getRepository(UsuarioCategoria::class)->find($request->request->get('cboUsuarioCategoria'));
            $usuario->setCategoria($categoria);

            $status=$this->getDoctrine()->getRepository(UsuarioStatus::class)->find($request->request->get('cboStatues'));
            $usuario->setStatus($status);
            $entityManager->persist($usuario);
            $entityManager->flush();

            $getcuentas=$_POST['cboEmpresa'];
         
            foreach($getcuentas as $getcuenta){
                $cuenta=$this->getDoctrine()->getRepository(Cuenta::class)->find($getcuenta);
                
                $usuarioCuenta=new UsuarioCuenta();

                $usuarioCuenta->setCuenta($cuenta);
                $usuarioCuenta->setUsuario($usuario);
                
                $entityManager->persist($usuarioCuenta);
                $entityManager->flush();
                $usuario->setEmpresaActual($cuenta->getEmpresa()->getId());
                $entityManager->persist($usuario);
                $entityManager->flush();
            }
            
   

            return $this->redirectToRoute('agendadores_index');
        }

        return $this->render('agendadores/new.html.twig', [
            'agendador' => $usuario,
            'form' => $form->createView(),
            'pagina'=>$pagina->getNombre(),
            'cuentas'=>$cuentas,
            'usuarioCategorias'=>$usuarioCategorias,
            'statues'=>$statues,
            'tipo_documentos'=>$tipoDocumento->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="agendadores_show", methods={"GET"})
     */
    public function show(Usuario $usuario,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','agendadores');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('agendadores',$user->getEmpresaActual());
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
            'pagina'=>$pagina->getNombre(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="agendadores_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, 
                        Usuario $usuario,
                        UsuarioTipoRepository $usuarioTipoRepository,
                        ModuloPerRepository $moduloPerRepository,
                        UsuarioTipoDocumentoRepository $tipoDocumento,
                        UserPasswordEncoderInterface $encoder,
                        UsuarioNoDisponibleRepository $usuarioNoDisponibleRepository): Response
    {
        $this->denyAccessUnlessGranted('edit','agendadores');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('agendadores',$user->getEmpresaActual());
        $empresa=$this->getDoctrine()->getRepository(Empresa::class)->find($user->getEmpresaActual());
        $usuarioCuenta=$this->getDoctrine()->getRepository(UsuarioCuenta::class)->findOneBy(['usuario'=>$usuario->getId()]);
   
        $usuarioCategorias=$empresa->getUsuarioCategorias();
        $cuentas=$empresa->getCuentas();

        $statues=$this->getDoctrine()->getRepository(UsuarioStatus::class)->findBy(['id'=>[1,2]]);
        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->add("password", TextType::class,[
            'required'   => false,
            'attr'=>[
                'style'=>'display:none'
            ],

        ]);
        $form->add("passwordAnt", TextType::class,[
            'required'   => false,
        ]);
        $form->add('sexo',ChoiceType::class,[
            'choices' =>[
                'Masculino'=>'Masculino',
                'Femenino'=>'Femenino'
            ],
        ]
        );

        $form->handleRequest($request);

        $horaInicio=$usuarioNoDisponibleRepository->getHoras();
        $horaFin=$usuarioNoDisponibleRepository->getHoras();

        if ($form->isSubmitted() && $form->isValid()) {
            if($usuario->getPasswordAnt()!=""){
                $password=$usuario->getPasswordAnt();
                $encoded=$encoder->encodePassword($usuario,$password);
                $usuario->setPassword($encoded);
                $usuario->setPasswordAnt("");
            }
            
            $this->getDoctrine()->getManager()->flush();
            
            $entityManager = $this->getDoctrine()->getManager();

            $categoria=$this->getDoctrine()->getRepository(UsuarioCategoria::class)->find($request->request->get('cboUsuarioCategoria'));
            $usuario->setCategoria($categoria);


            $usuario->setTipoDocumento($tipoDocumento->find($request->request->get('cboTipoDocumento')));

            $status=$this->getDoctrine()->getRepository(UsuarioStatus::class)->find($request->request->get('cboStatues'));
            $usuario->setStatus($status);
            

            $usuarioCuentas=$usuario->getUsuarioCuentas();
            foreach($usuarioCuentas as $usuarioCuenta){
                $usuario->removeUsuarioCuenta($usuarioCuenta);
            }
            $getcuentas=$_POST['cboEmpresa'];
         
            foreach($getcuentas as $getcuenta){
                $cuenta=$this->getDoctrine()->getRepository(Cuenta::class)->find($getcuenta);
                
                $usuarioCuenta=new UsuarioCuenta();

                $usuarioCuenta->setCuenta($cuenta);
                $usuarioCuenta->setUsuario($usuario);
                
                $entityManager->persist($usuarioCuenta);
                $entityManager->flush();
                if(is_null($usuario->getEmpresaActual())){
                    $usuario->setEmpresaActual($cuenta->getEmpresa()->getId());

                }
            }
            $entityManager->persist($usuario);
            $entityManager->flush();
            
            return $this->redirectToRoute('agendadores_index');
        }

        return $this->render('agendadores/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'pagina'=>$pagina->getNombre(),
            'cuentas'=>$cuentas,
            'usuarioCategorias'=>$usuarioCategorias,

            'statues'=>$statues,
            'id_cuenta'=>$usuarioCuenta->getCuenta()->getId(),
            'cuentas_sel'=>$usuario->getUsuarioCuentas(),
            'tipo_documentos'=>$tipoDocumento->findAll(),
            'hora_inicio'=>$horaInicio,
            'hora_fin'=>$horaFin,
        ]);
    }

    /**
     * @Route("/{id}", name="agendadores_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        $this->denyAccessUnlessGranted('full','agendadores');
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $usuario->setEstado(0);
            $entityManager->persist($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agendadores_index');
    }
}
