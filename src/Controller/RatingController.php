<?php
// src/Controller/RatingController.php
namespace App\Controller;

use DateTime;
use App\Entity\Review;
use App\Entity\User;
use App\Entity\Placetovisit;
use App\Entity\PlaceReviews;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Form\PlacetovisitType;
use App\Form\PlaceReviewsType;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[IsGranted('IS_AUTHENTICATED')]
class RatingController extends AbstractController
{
    #[Route('/placetovisit/{placeId}/rating', name: 'rating1', methods: ['GET', 'POST'])]
    public function saveRating(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapEntity] Placetovisit $place,
    ): Response
    {
        $ratesform = $entityManager
        ->getRepository(Review::class)
        ->findAll();


        $idUser = $this->getUser();

        $rating = $entityManager->getRepository(Review::class)->findOneBy([
            'idUser' => $idUser->getIdUser(),
            'place' => $place->getPlaceId(),
        ]);
        dump($idUser);
        $reviewForm = $this->createForm(PlaceReviewsType::class);
        $reviewForm->handleRequest($request);
        if ($request->request->get('save')) {
            $ratedIndex = $request->request->get('ratedIndex');
            $reviewTxt = $request->request->get('reviewTxt'); // Retrieve the value of reviewTxt from the AJAX request
            if ($ratedIndex !== null && $ratedIndex >= 1 && $ratedIndex <= 5) {
                if ($rating) {
                    $rating->setRating($ratedIndex);
                    $rating->setReviewTxt($reviewTxt);
                } else {
                $rating = new Review();
                $rating->setIdUser($idUser->getIdUser());
                $rating->setPlaceName($place->getPlaceName());
                $rating->setReviewDate(new DateTime());
                $rating->setPlace($place);
                $rating->setReviewTxt($reviewTxt);
                $rating->setRating($ratedIndex);
                $entityManager->persist($rating);
                }
                if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
                    $review = $reviewForm->getData();
                    $review->setReviewTxt($reviewTxt); 
                    $entityManager->persist($review);
                }
                $entityManager->flush();
            }
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode(['idUser' => $idUser, 'reviewTxt' => $reviewTxt]));
            return $response;
        }
        return $this->render('rating/rating.html.twig', [
            'idUser' => $idUser,
            'place' => $place,
            'reviewForm' => $reviewForm->createView(),
            'ratesform' => $ratesform,
        ]);
    }


        // public function ReviewForm(Placetovisit $place): Response
    // {
    //     $review = new PlaceReviews();
    //     $review->setPlace($place);
    //     $form = $this->createForm(PlaceReviewsType::class, $review);
    
    //     return $this->render('place_reviews/_form.html.twig', [
    //         'reviewTxt' => $form->get('reviewTxt')->createView(),
    //     ]);
    // }
    
}
