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


#[Route('/placetovisit')]
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $placetovisit = new Placetovisit();
        $form = $this->createForm(PlacetovisitType::class, $placetovisit)
        ->add('Save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($placetovisit);
            $entityManager->flush();
    
            $this->addFlash('success', 'Place to visit added!');
            $form = $form->get('Save');

    
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
    public function edit(Request $request, Placetovisit $placetovisit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlacetovisitType::class, $placetovisit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
