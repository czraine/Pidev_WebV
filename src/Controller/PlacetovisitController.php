<?php

namespace App\Controller;

use App\Entity\Placetovisit;
use App\Form\PlacetovisitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\UploaderService;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Attribute\IsGranted;






#[Route('admin/placetovisit'),IsGranted('Admin')]
class PlacetovisitController extends AbstractController
{
    #[Route('/', name: 'app_placetovisit_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $placetovisits = $entityManager
            ->getRepository(Placetovisit::class)
            ->findAll();

        return $this->render('placetovisit/index.html.twig', [
            'placetovisits' => $placetovisits,
        ]);
    }

    #[Route('/new', name: 'app_placetovisit_new', methods: ['GET', 'POST'])]
    public function new(/*#[CurrentUser] User $idUser,*/Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $placetovisit = new Placetovisit();
        $form = $this->createForm(PlacetovisitType::class, $placetovisit)
            ->add('Save', SubmitType::class);
        $form->handleRequest($request);
        // dump($idUser);
        dump($this->getUser());


        if ($form->isSubmitted() && $form->isValid()) {
            $photo1 = $form->get('placeImg')->getData();
            if ($photo1) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg($uploaderService->uploadFile($photo1, $directory));
            }
    
            $photo2 = $form->get('placeImg2')->getData();
            if ($photo2) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg2($uploaderService->uploadFile($photo2, $directory));
            }
    
            $photo3 = $form->get('placeImg3')->getData();
            if ($photo3) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg3($uploaderService->uploadFile($photo3, $directory));
            }
            
            $entityManager->persist($placetovisit);
            $entityManager->flush();
            $this->addFlash('success', 'Place to visit added!');
            return $this->redirectToRoute('app_placetovisit_index');
        }
    
        return $this->render('placetovisit/new.html.twig', [
            'placetovisit' => $placetovisit,
            'form' => $form->createView(),
        ]);
    }
    
    
    
    #[Route('/{placeId}', name: 'app_placetovisit_show', methods: ['GET'])]
    public function show(Placetovisit $placetovisit): Response
    {
        return $this->render('placetovisit/show.html.twig', [
            'placetovisit' => $placetovisit,
        ]);
    }
    
    

    #[Route('/{placeId}/edit', name: 'app_placetovisit_edit', methods: ['GET', 'POST'])]
    public function edit( UploaderService $uploaderService,Request $request, Placetovisit $placetovisit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlacetovisitType::class, $placetovisit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo1 = $form->get('placeImg')->getData();
            if ($photo1) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg($uploaderService->uploadFile($photo1, $directory));
            }
    
            $photo2 = $form->get('placeImg2')->getData();
            if ($photo2) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg2($uploaderService->uploadFile($photo2, $directory));
            }
    
            $photo3 = $form->get('placeImg3')->getData();
            if ($photo3) {
                $directory = $this->getParameter('images_directory');
                $placetovisit->setPlaceImg3($uploaderService->uploadFile($photo3, $directory));
            } else {
                $message = " a été mis à jour avec succès";
            }
            $entityManager->persist($placetovisit);
            $entityManager->flush();
            return $this->redirectToRoute('app_placetovisit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('placetovisit/edit.html.twig', [
            'placetovisit' => $placetovisit,
            'form' => $form,
        ]);
    }

    #[Route('/{placeId}', name: 'app_placetovisit_delete', methods: ['POST'])]
    public function delete(Request $request, Placetovisit $placetovisit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$placetovisit->getPlaceId(), $request->request->get('_token'))) {
            $entityManager->remove($placetovisit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_placetovisit_index', [], Response::HTTP_SEE_OTHER);
    }
}
