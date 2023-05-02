<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tourist')]
class TouristController extends AbstractController
{
    #[Route('/', name: 'app_tourist_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('tourist/index.html.twig', [
            'users' => $users,
            'userconnected'=>$userconnected
        ]);
    }

    #[Route('/new', name: 'app_tourist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $utilisateurController = new UserController();
        $userconnected=$utilisateurController->getDataUserConnected($session);
        $user = new User();
        $form = $this->createForm(User2Type::class, $user);
      
        $form->remove("role");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole("TOURIST");
            $hashPwd=sha1($user->getPassword());
            $user->setPassword($hashPwd);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_tourist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tourist/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'userconnected'=>$userconnected
        ]);
    }

    #[Route('/{idUser}', name: 'app_tourist_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('tourist/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{idUser}/edit', name: 'app_tourist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(User2Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPwd=sha1($user->getPassword());
            $user->setPassword($hashPwd);

            $entityManager->flush();

            return $this->redirectToRoute('app_tourist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tourist/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{idUser}', name: 'app_tourist_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_tourist_index', [], Response::HTTP_SEE_OTHER);
    }
}
