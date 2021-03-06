<?php

namespace App\Controller;
use App\Entity\Usuario;
use App\Entity\UsuarioCategoria;
use App\Entity\Empresa;
use App\Entity\UsuarioCuenta;
use App\Entity\Cuenta;
use App\Entity\UsuarioStatus;
use App\Entity\Privilegio;
use App\Entity\PrivilegioTipousuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use App\Repository\UsuarioTipoRepository;
use App\Repository\ModuloPerRepository;
use App\Repository\UsuarioTipoDocumentoRepository;
use App\Repository\PrivilegioTipousuarioRepository;
use App\Repository\PrivilegioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/jefe_abogados")
 */
class JefeAbogadosController extends AbstractController
{
    /**
     * @Route("/", name="jefe_abogados_index",methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository,
                    ModuloPerRepository $moduloPerRepository,
                    PaginatorInterface $paginator,
                    Request $request): Response
    {
        $this->denyAccessUnlessGranted('view','jefe_abogados');
        $user=$this->getUser();
        $modo=1;
        if($request->query->get('modo')=='trash'){
            $modo=0;
            
        }
        $pagina=$moduloPerRepository->findOneByName('jefe_abogados',$user->getEmpresaActual());
        $query=$usuarioRepository->findBy(['usuarioTipo'=>4,'estado'=>$modo]);
        $usuarios=$paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/,
            array('defaultSortFieldName' => 'nombre', 'defaultSortDirection' => 'asc'));

        return $this->render('jefe_abogados/index.html.twig', [
            'usuarios' => $usuarios,
            'pagina'=>$pagina->getNombre(),
            'modo'=>$modo,
        ]);
    }

    /**
     * @Route("/new", name="jefe_abogados_new", methods={"GET","POST"})
     */
    public function new(Request $request,
                    UserPasswordEncoderInterface $encoder,
                        UsuarioTipoRepository $usuarioTipoRepository,
                        ModuloPerRepository $moduloPerRepository,
                        PrivilegioTipousuarioRepository $privilegioTipousuarioRepository,
                        PrivilegioRepository $privilegioRepository,
                        UsuarioTipoDocumentoRepository $tipoDocumento): Response
    {
        $this->denyAccessUnlessGranted('create','jefe_abogados');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('jefe_abogados',$user->getEmpresaActual());
        $usuario = new Usuario();
        $usuario->setEstado(1);
        $empresa=$this->getDoctrine()->getRepository(Empresa::class)->find($user->getEmpresaActual());
        $cuentas=$empresa->getCuentas();
        $choices= array();
        
       
        
        $usuario->setUsuarioTipo($usuarioTipoRepository->find(4));
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

            $privilegioTipousuarios=$privilegioTipousuarioRepository->findBy(['tipousuario'=>$usuario->getUsuarioTipo()->getId()]);
            foreach($privilegioTipousuarios as $privilegioTipousuario){
                $privilegio=$privilegioRepository->findBy(["moduloPer"=>$privilegioTipousuario->getModuloPer()->getId(),"usuario"=>$usuario->getId()]);
                if(!$privilegio){
    
                    $privilegioNew=new Privilegio();
                    $privilegioNew->setUsuario($usuario);
                    $privilegioNew->setModuloPer($privilegioTipousuario->getModuloPer());
                    $privilegioNew->setAccion($privilegioTipousuario->getAccion());
    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($privilegioNew);
                    $entityManager->flush();
    
                }
            }

            return $this->redirectToRoute('jefe_abogados_index');
        }

        return $this->render('jefe_abogados/new.html.twig', [
            'agendador' => $usuario,
            'form' => $form->createView(),
            'pagina'=>$pagina->getNombre(),
            'cuentas'=>$cuentas,
            
            'tipo_documentos'=>$tipoDocumento->findAll(),
            
        ]);
    }

    /**
     * @Route("/{id}", name="jefe_abogados_show", methods={"GET"})
     */
    public function show(Usuario $usuario,ModuloPerRepository $moduloPerRepository): Response
    {
        $this->denyAccessUnlessGranted('view','jefe_abogados');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('jefe_abogados',$user->getEmpresaActual());
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
            'pagina'=>$pagina->getNombre(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="jefe_abogados_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, 
                        Usuario $usuario,
                        UsuarioTipoRepository $usuarioTipoRepository,
                        ModuloPerRepository $moduloPerRepository,
                        UsuarioTipoDocumentoRepository $tipoDocumento,
                        UserPasswordEncoderInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('edit','jefe_abogados');
        $user=$this->getUser();
        $pagina=$moduloPerRepository->findOneByName('jefe_abogados',$user->getEmpresaActual());
        $empresa=$this->getDoctrine()->getRepository(Empresa::class)->find($user->getEmpresaActual());
        $usuarioCuenta=$this->getDoctrine()->getRepository(UsuarioCuenta::class)->findOneBy(['usuario'=>$usuario->getId()]);
   
       
        $cuentas=$empresa->getCuentas();

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

        if ($form->isSubmitted() && $form->isValid()) {
        
            if($usuario->getPasswordAnt()!=""){
                $password=$usuario->getPasswordAnt();
                $encoded=$encoder->encodePassword($usuario,$password);
                $usuario->setPassword($encoded);
                $usuario->setPasswordAnt("");
            }
            
            $this->getDoctrine()->getManager()->flush();
            
            $entityManager = $this->getDoctrine()->getManager();

            

            $usuario->setTipoDocumento($tipoDocumento->find($request->request->get('cboTipoDocumento')));
            

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
            
            return $this->redirectToRoute('jefe_abogados_index');
        }

        return $this->render('jefe_abogados/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
            'pagina'=>$pagina->getNombre(),
            'cuentas'=>$cuentas,
            'cuentas_sel'=>$usuario->getUsuarioCuentas(),
            'tipo_documentos'=>$tipoDocumento->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/restore", name="jefe_abogados_restore", methods={"GET"})
     */
    public function restore(Request $request, Usuario $usuario): Response
    {
        $this->denyAccessUnlessGranted('full','jefe_abogados');
      
            $entityManager = $this->getDoctrine()->getManager();
            $usuario->setEstado(1);
            $entityManager->persist($usuario);
            $entityManager->flush();
     

        return $this->redirectToRoute('jefe_abogados_index');
    }
    /**
     * @Route("/{id}", name="jefe_abogados_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        $this->denyAccessUnlessGranted('full','jefe_abogados');
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $usuario->setEstado(0);
            $entityManager->persist($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jefe_abogados_index');
    }
}
