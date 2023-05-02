<?php

namespace App\Controller\Front;

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

use App\Service\WeatherService;
use App\Entity\OpenWeatherMapForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;






#[Route('/placetovisit')]
class UserPlacetovisitController extends AbstractController
{




    // #[Route('/', name: 'front_placetovisit_index', methods: ['GET'])]
    // public function index(EntityManagerInterface $entityManager): Response
    // {
    //     $placetovisits = $entityManager
    //         ->getRepository(Placetovisit::class)
    //         ->findAll();

    //     return $this->render('Front/placetovisit/index.html.twig', [
    //         'placetovisits' => $placetovisits,
    //     ]);
    // }



    private $weatherService;

  public function __construct(WeatherService $weather)
  {
    $this->weatherService = $weather;
  }
      #[Route('/', name: 'front_placetovisit_index', methods: ['GET'])]

  public function number(EntityManagerInterface $entityManager)
  {
    $placetovisits = $entityManager
            ->getRepository(Placetovisit::class)
            ->findAll();
    // data generation
    // source: https://github.com/wadday/openweather/blob/master/src/Wadday/Openweather/Wadday.php
    $data = $this->weatherService->getWeather('tunis');
    // dump($data);
    if (is_array($data)) {
      return $this->render('Front/placetovisit/index.html.twig', [
        'data' => $data,
      'placetovisits' => $placetovisits,]);
    } else {
      $statusCode = 0;
      $errorMessage = '';
      $e = $data;
      if (method_exists($e, 'getResponse')) {
        $statusCode = $e->getResponse()->getStatusCode();
      }
      if ($statusCode == 0) {
        $errorMessage = 'Error occurs';
      }
      if (401 == $statusCode) {
        $errorMessage = "API calls return an error 401.
          You can get the error 401 in the following cases:
          You did not specify your API key in API request.
          Your API key is not activated yet. Within the next couple of hours, it will be activated and ready to use.
          You are using wrong API key in API request. Please, check your right API key in personal account.
          You have free subscription and try to get access to our paid services (for example, 16 days/daily forecast API, any historical weather data, Weather maps 2.0, etc). Please, check your tariff in your personal account and pay attention at our price and condition.
          Starting from 9 October 2015 our API requires a valid APPID for access. Note that this does not mean that our API is subscription-only now - please take a minute to register a free account to receive a key.
          For FOSS developers: we welcome free and open source software and are willing to help you. If you want to use OpenWeather data in your free software application please register an API key and file a ticket describing your application and API key registered. OpenWeather will review your request lift access limits for your key if used in open source application.";
      }
      if (404 == $statusCode) {
        $errorMessage = "API calls return an error 404.
          You can get this error in the following cases:
          You make a wrong API request. Please, check your API request. The detail documentation about any our weather services is here.
          You specify wrong city name, ZIP-code or city ID.
          This list contains the following data by locations in our system:
          City name
          City ID
          Geographical coordinates of the city (lon, lat)
          Zoom, etc";
      }
      if (429 == $statusCode) {
        $errorMessage = "API calls return an error 429.
          You will get the error 429 if you have free tariff and make more than 60 API calls per minute.
          Please switch to a subscription plan that meets your needs or reduce the number of API calls in accordance with the established limits.";
      }
      return $this->render('errors.html.twig', ['error' => $errorMessage]);
    }
  }


    #[Route('/new', name: 'front_placetovisit_new', methods: ['GET', 'POST'])]
    public function new(/*#[CurrentUser] User $idUser,*/Request $request, EntityManagerInterface $entityManager, UploaderService $uploaderService): Response
    {
        $placetovisit = new Placetovisit();
        $form = $this->createForm(PlacetovisitType::class, $placetovisit)
            ->add('Save', SubmitType::class);
        $form->handleRequest($request);
        // dump($idUser);
        // dump($this->getUser());


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
            return $this->redirectToRoute('front_placetovisit_index');
        }
    
        return $this->render('Front/placetovisit/new.html.twig', [
            'placetovisit' => $placetovisit,
            'form' => $form->createView(),
        ]);
    }
    
    
    
    #[Route('/{placeId}', name: 'front_placetovisit_show', methods: ['GET'])]
    public function show(Placetovisit $placetovisit): Response
    {
        return $this->render('Front/placetovisit/show.html.twig', [
            'placetovisit' => $placetovisit,
        ]);
    }
    
    

    #[Route('/{placeId}/edit', name: 'front_placetovisit_edit', methods: ['GET', 'POST'])]
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
            return $this->redirectToRoute('front_placetovisit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front/placetovisit/edit.html.twig', [
            'placetovisit' => $placetovisit,
            'form' => $form,
        ]);
    }

    #[Route('/{placeId}', name: 'front_placetovisit_delete', methods: ['POST'])]
    public function delete(Request $request, Placetovisit $placetovisit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$placetovisit->getPlaceId(), $request->request->get('_token'))) {
            $entityManager->remove($placetovisit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('front_placetovisit_index', [], Response::HTTP_SEE_OTHER);
    }
}
