<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Reservationcircuit;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\CircuitType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/circuit')]
class CircuitController extends AbstractController
{
    #[Route('/', name: 'app_circuit_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $circuits = $entityManager
            ->getRepository(Circuit::class)
            ->findAll();

        return $this->render('circuit/index.html.twig', [
            'circuits' => $circuits,
        ]);
    }
    #[Route('/ez/z', name: 'app_circuit_index_e', methods: ['GET'])]
    public function e(EntityManagerInterface $entityManager): Response
    {
        $circuits = $entityManager
            ->getRepository(Circuit::class)
            ->findAll();

        return $this->render('front/email.html.twig', [
            'circuits' => $circuits,
        ]);
    }
    #[Route('/liste', name: 'app_circuit_index_client', methods: ['GET'])]
    public function indexClient(EntityManagerInterface $entityManager): Response
    {
        $circuits = $entityManager
            ->getRepository(Circuit::class)
            ->findAll();
        
        return $this->render('front/listecircuit.html.twig', [
            'circuits' => $circuits,
        ]);
    }


    #[Route('/new', name: 'app_circuit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $circuit = new Circuit();
        
        $form = $this->createForm(CircuitType::class, $circuit,[
            'villes' => $entityManager->getRepository(Ville::class)
            ->findAll()
        ]);
        
        $villes=$entityManager->getRepository(Ville::class)->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('imagesrc')->getData();
            if ($imgFile) {
                $newFilename = uniqid() . '.' . $imgFile->guessExtension();
                $imgFile->move(
                    $this->getParameter('product_images_directory'),
                    $newFilename
                );
            $circuit->setImagesrc($newFilename);
        }
        $circuit->setNbPlace($circuit->getNbPlace());

            $circuit->setVarr($villes[intval($circuit->getVarr())-1]->getNomville());
            $circuit->setVdep($villes[intval($circuit->getVdep())-1]->getNomville());

            $entityManager->persist($circuit);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit/new.html.twig', [
            'circuit' => $circuit,
            'form' => $form,
        ]);
    }

    #[Route('/{nc}', name: 'app_circuit_show', methods: ['GET'])]
    public function show(Circuit $circuit): Response
    {
        return $this->render('circuit/show.html.twig', [
            'circuit' => $circuit,
        ]);
    }

    #[Route('/{nc}/edit', name: 'app_circuit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Circuit $circuit, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(CircuitType::class, $circuit,[
            'villes' => $entityManager->getRepository(Ville::class)
            ->findAll()
        ]);
        $villes=$entityManager->getRepository(Ville::class)->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('imagesrc')->getData();
            if ($imgFile) {
                $newFilename = uniqid() . '.' . $imgFile->guessExtension();
                $imgFile->move(
                    $this->getParameter('product_images_directory'),
                    $newFilename
                );
            $circuit->setNbPlace($circuit->getNbPlace());
            $circuit->setImagesrc($newFilename);

        }
            $circuit->setVarr($villes[$circuit->getVarr()]->getNomville());
            $circuit->setVdep($villes[$circuit->getVdep()]->getNomville());

            $entityManager->persist($circuit);
            $entityManager->flush();

          
            return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit/edit.html.twig', [
            'circuit' => $circuit,
            'form' => $form,
        ]);
    }

    #[Route('/{nc}', name: 'app_circuit_delete', methods: ['POST'])]
    public function delete(Request $request, Circuit $circuit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$circuit->getNc(), $request->request->get('_token'))) {
            $entityManager->remove($circuit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
    }
  
    #[Route('/{nc}/available', name: 'app_circuit_getNBplaceAvailable', methods: ['GET'])]
    public function getNbPlaceAvailable(Request $request, Circuit $circuit, EntityManagerInterface $entityManager): Response
    {
        $nc = $request->query->get('nbplace');

        return  $this->json($circuit->getNbPlace()>=$nc);
    }
    #[Route('/{nc}/res', name: 'app_circuit_res', methods: ['POST'])]
    public function res(Request $request, Circuit $circuit, EntityManagerInterface $entityManager): Response
    {
         $res=new Reservationcircuit();

         $date = new DateTime($request->request->get("date"));
        $res->setDatedebut($date);
        $res->setNbrPlace($request->request->get('nb'));
        $res->setIsPaid(false);
        $user=$entityManager
        ->getRepository(User::class)
        ->findAll();
        $res->setIdClient($user[0]);
        $res->setNc($circuit);
        $entityManager->persist($res);
        $entityManager->flush();
        return $this->redirectToRoute('app_circuit_index_client', [], Response::HTTP_SEE_OTHER);


        
    }
  
    #[Route('/show/{nc}', name: 'app_circuit_show', methods: ['GET'])]
    public function showevent( Circuit $circuit,EntityManagerInterface $entityManager): Response
    {
        
        $cordDeb=$entityManager
        ->getRepository(Ville::class)
        ->findOneBy(['nomville'=>$circuit->getVdep()]);
        $cordArr=$entityManager
        ->getRepository(Ville::class)
        ->findOneBy(['nomville'=>$circuit->getVarr()]);
        
        return $this->render('front/show_circuit.html.twig', [
            'circuit' => $circuit,
            'cordDeb'=>$cordDeb,
            'cordArr'=>$cordArr


        ]);
    }

    
}
