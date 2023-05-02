<?php

namespace App\Controller;

use App\Entity\Placetovisit;
use App\Entity\PlaceReviews;
use App\Form\PlacetovisitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('json'),IsGranted('Admin')]
class JsonPlaceToVisiteController extends AbstractController
{
    #[Route('/visit/show', methods: ['GET'], name: 'show_place')]
      public function showPlace(EntityManagerInterface $entityManager,SerializerInterface $serializer)
      {
        $placetovisits = $entityManager
            ->getRepository(Placetovisit::class)
            ->findAll();
        $json = $serializer->serialize($placetovisits, 'json');
        $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
        return new JsonResponse(json_decode($json));
      }

      #[Route('/visit/new', name: 'app_new_place', methods: ['POST'])]
      public function newPlaceAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
      {
          $placetovisit = new Placetovisit();
          $name = $request->get('name');
          $city = $request->get('city');
          $type = $request->get('type');
          $desc = $request->get('desc');
          $address = $request->get('address');
          $price = $request->get('price');
          $img = $request->get('img');
          $img2 = $request->get('img2');
          $img3 = $request->get('img3');
      
          $placetovisit->setPlaceName($name);
          $placetovisit->setCityname($city);
          $placetovisit->setPlaceType($type);
          $placetovisit->setPlaceDescription($desc);
          $placetovisit->setPlaceAddress($address);
          $placetovisit->setTicketsPrice($price);
          $placetovisit->setPlaceImg($img);
          $placetovisit->setPlaceImg2($img2);
          $placetovisit->setPlaceImg3($img3);
      
          $entityManager->persist($placetovisit);
          $entityManager->flush();
      
          $json = $serializer->serialize($placetovisit, 'json');
          $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
          $formatted = $serializer->normalize($placetovisit);
          return new JsonResponse($formatted);
      }

    #[Route('/visit/edit/{id}', name: 'app_place_edit_json', methods: ['GET', 'POST'])]
    public function editPlaceAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, $id): JsonResponse
    {
        $placetovisit = $entityManager->getRepository(Placetovisit::class)->find($id);
    
        if (!$placetovisit) {
            $formatted = ['error' => 'Place not found.'];
            return new JsonResponse($formatted);
        }
    
        $name = $request->get('name');
        $city = $request->get('city');
        $type = $request->get('type');
        $desc = $request->get('desc');
        $address = $request->get('address');
        $price = $request->get('price');
        $img = $request->get('img');
        $img2 = $request->get('img2');
        $img3 = $request->get('img3');
    
        $placetovisit->setPlaceName($name);
        $placetovisit->setCityName($city);
        $placetovisit->setPlaceType($type);
        $placetovisit->setPlaceDescription($desc);
        $placetovisit->setPlaceAddress($address);
        $placetovisit->setTicketsPrice($price);
        $placetovisit->setPlaceImg($img);
        $placetovisit->setPlaceImg2($img2);
        $placetovisit->setPlaceImg3($img3);
    
        $entityManager->flush();
    
        $json = $serializer->serialize($placetovisit, 'json');
        $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
        $formatted = $serializer->normalize($placetovisit);
        return new JsonResponse($formatted);
    }

    #[Route('/visit/delete', name: 'app_place_delete_json', methods: ['GET'])]   
    public function deletePlaceAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
            $id = $request->get("id");
            $placetovisit = $entityManager->getRepository(Placetovisit::class)->find($id);
            if($placetovisit!=null ) {
                $entityManager->remove($placetovisit);
                $entityManager->flush();


                $serialize = new Serializer([new ObjectNormalizer()]);
                $formatted = $serialize->normalize("place a ete supprimee avec success.");
                return new JsonResponse($formatted);
            }
        return new JsonResponse("id reclamation invalide.");
    }

    // =====================================================================================================================
    #[Route('/reviews/show', methods: ['GET'], name: 'show_Reviews')]
    public function showReviews(EntityManagerInterface $entityManager,SerializerInterface $serializer)
    {
    //   $PlaceReviews = $entityManager
    //       ->getRepository(PlaceReviews::class)
    //       ->findAll();

          $PlaceReviews = $entityManager
          ->getRepository(PlaceReviews::class)
          ->createQueryBuilder('p')
          ->orderby('p.id')
          ->select('p.id,p.placeName,p.rating,p.reviewTxt,p.reviewDate')
          ->getQuery()
          ->getResult();

      $json = $serializer->serialize($PlaceReviews, 'json');
      $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
      return new JsonResponse(json_decode($json));
    }

    #[Route('/reviews/visit/new', name: 'app_new_reviews', methods: ['POST'])]
    public function newReviewsAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $PlaceReviews = new PlaceReviews();
        
        

        $Placeid = $request->get('Place');
        $Place = $entityManager->getRepository(Placetovisit::class)->find($Placeid);
        $User = $request->get('IdUser');
        $IdUser = $entityManager->getRepository(User::class)->find($User);
        $name = $request->get('name');
        $rating = $request->get('rating');
        $ReviewTxt = $request->get('ReviewTxt');
        $ReviewDate = new \DateTime('now');
    
        $PlaceReviews->setPlaceName($name);
        $PlaceReviews->setRating($rating);
        $PlaceReviews->setReviewTxt($ReviewTxt);
        $PlaceReviews->setPlace($Place);
        $PlaceReviews->setIdUser($IdUser);
        $PlaceReviews->setReviewDate($ReviewDate);

    
        $entityManager->persist($PlaceReviews);
        $entityManager->flush();
    
        $json = $serializer->serialize($PlaceReviews, 'json');
        $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
        $formatted = $serializer->normalize($PlaceReviews);
        return new JsonResponse($formatted);
    }

    #[Route('/reviews/edit/{id}', name: 'app_Reviews_edit_json', methods: ['GET', 'POST'])]
    public function editReviewsAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, $id): JsonResponse
    {
        $PlaceReviews = $entityManager->getRepository(PlaceReviews::class)->find($id);
    
        if (!$PlaceReviews) {
            $formatted = ['error' => 'Place not found.'];
            return new JsonResponse($formatted);
        }
        $Placeid = $request->get('Place');
        $Place = $entityManager->getRepository(Placetovisit::class)->find($Placeid);
        $User = $request->get('IdUser');
        $IdUser = $entityManager->getRepository(User::class)->find($User);
        $name = $request->get('name');
        $rating = $request->get('rating');
        $ReviewTxt = $request->get('ReviewTxt');
        $ReviewDate = new \DateTime('now');
    
        $PlaceReviews->setPlaceName($name);
        $PlaceReviews->setRating($rating);
        $PlaceReviews->setReviewTxt($ReviewTxt);
        $PlaceReviews->setPlace($Place);
        $PlaceReviews->setIdUser($IdUser);
        $PlaceReviews->setReviewDate($ReviewDate);
    
        $entityManager->flush();
    
        $json = $serializer->serialize($PlaceReviews, 'json');
        $formatted = $serializer->serialize($json, 'json', ['groups' => ['normal']]);
        $formatted = $serializer->normalize($PlaceReviews);
        return new JsonResponse($formatted);
    }
    #[Route('/reviews/delete', name: 'app_Reviews_delete_json', methods: ['GET'])]   
    public function deleteReviewsAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
            $id = $request->get("id");
            $PlaceReviews = $entityManager->getRepository(PlaceReviews::class)->find($id);
            if($PlaceReviews!=null ) {
                $entityManager->remove($PlaceReviews);
                $entityManager->flush();


                $serialize = new Serializer([new ObjectNormalizer()]);
                $formatted = $serialize->normalize("review a ete supprimee avec success.");
                return new JsonResponse($formatted);
            }
        return new JsonResponse("id reclamation invalide.");
    }
}