<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(Request $request,UtilisateurRepository $repository,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);

        $emailerror=false;

        $session=$request->getSession();

        //If session existe
        if ($session->has('user')){
            $user1=$this->getDataUserConnected($session);
            if ($user1->getRole() == "Admin")
            {
                return $this->redirectToRoute("app_admin");
            }
            else if ($user1->getRole() == "Guide")
            {
                return $this->redirectToRoute("app_guide");
            }
            else if ($user1->getRole() == "Touriste")
            {
                return $this->redirectToRoute("app_touriste");
            }
        }
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('username',TextType::class, [
                'label' => 'UserName','attr'=>['class'=>'form-control']
            ])
            ->add('password',PasswordType::class, [
                'label' => 'Password','attr'=>['class'=>'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() )
        {
            $pwd = $user->getPassword();
            $login = $user->getUsername();
          

            $Existe =$repository->findOneBy(array('username'=>$login,'password'=>sha1($pwd)));

            if (!$Existe)
            {
                $emailerror=true;
            }else
            {
                $myObj = new stdClass();
                $myObj->Id = $Existe->getIdUser();
                $myObj->nom = $Existe->getUserLastname();
                $myObj->prenom = $Existe->getUserFirstname();
                $myObj->email = $Existe->getUserMail();
                $myObj->login = $Existe->getUsername();
                $myObj->rank = $Existe->getRole();
                $myObj->location = $Existe->getNationality();
                $myObj->tel = $Existe->getUserPhone();
                $myJSON = json_encode($myObj);

                $obj = json_decode($myJSON,true);
                $session->set('user', $obj);

                return $this->redirectToRoute("app_user_index");
            }
        }
        return $this->render('user/login.html.twig',[
            'user' => $user,
            'form' => $form->createView(),
            'emailerror'=>$emailerror,
            'userconnected'=>$userconnected
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_user_index');
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);

        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'userconnected'=>$userconnected
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->remove("role");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole("Admin");
            $hashPwd=sha1($user->getPassword());
            $user->setPassword($hashPwd);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'userconnected'=>$userconnected
        ]);
    }

    #[Route('/{idUser}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);
        return $this->render('user/show.html.twig', [
            'user' => $user,'userconnected'=>$userconnected
        ]);
    }

    #[Route('/{idUser}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPwd=sha1($user->getPassword());
            $user->setPassword($hashPwd);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,'userconnected'=>$userconnected
        ]);
    }

    #[Route('/{idUser}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getDataUserConnected(SessionInterface $session)
    {
        $userConnected = new User();
        if ($session->has('user')) {
            $user = ($session->get('user'));
            $ar = json_encode($user);
            $aee = json_decode($ar);
            $userConnected->setIdUser($aee->Id);
            $userConnected->setUserLastname($aee->nom);
            $userConnected->setUserFirstname($aee->prenom);
            $userConnected->setUserMail($aee->email);
            $userConnected->setUsername($aee->login);
            $userConnected->setRole($aee->rank);
            $userConnected->setCityname($aee->location);
            $userConnected->setUserPhone($aee->tel);
        }

        return $userConnected;
    }




}
