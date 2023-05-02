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
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;

class RatingController extends AbstractController
{
    public function __construct  (private MailerInterface $mailer){}
    #[Route('/placetovisit/{placeId}/rating', name: 'rating1', methods: ['GET', 'POST'])]
    public function saveRating(
        Request $request,
        MailerInterface  $mailer,
        EntityManagerInterface $entityManager,
        #[MapEntity] Placetovisit $place,
        SessionInterface $session,
        UserController $utilisateurController,
        UtilisateurRepository $utilisateurRepository
    ): Response
    { 
        $utilisateurController = new UserController();
        $userconnected = $utilisateurController->getDataUserConnected($session);
        $idUser=$utilisateurRepository->findByIDobject($userconnected->getIdUser());
        // var_dump($idUser);
        $ratesform = $entityManager
        ->getRepository(Review::class)
        ->findAll();


        // $idUser = $this->getUser();
        // $username = $this->getUser()->getUsername();
        // $username=$utilisateurRepository->findByIDobject($userconnected->getUsername());
        $username=$idUser->getUsername();
        $emailuser=$idUser->getUserMail();
        // var_dump($emailuser);
        // echo($username);
        $rating = $entityManager->getRepository(Review::class)->findOneBy([
            'idUser' => $idUser,
            'place' => $place->getPlaceId(),
        ]);
        // var_dump($rating);

        // var_dump($idUser);
        $reviewForm = $this->createForm(PlaceReviewsType::class);
        $reviewForm->handleRequest($request);
        if ($request->request->get('save')) {
            $ratedIndex = $request->request->get('ratedIndex');
            $reviewTxt = $request->request->get('reviewTxt'); // Retrieve the value of reviewTxt from the AJAX request
            if ($ratedIndex !== null && $ratedIndex >= 1 && $ratedIndex <= 5) {
                if ($rating) {
                    $rating->setRating($ratedIndex);
                    $rating->setReviewTxt($reviewTxt);
                $email = (new TemplatedEmail())
                ->from('admin@RoadRevel.com')
                ->to($emailuser)
                ->subject('Demande de contact')
                ->htmlTemplate('email.html.twig')
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => $username,
                ])
            ;
            $mailer->send($email);{}
                } else {
                    $email = (new TemplatedEmail())
                    ->from('admin@RoadRevel.com')
                    ->to($emailuser)
                    ->subject('Demande de contact')
                    ->text('sdfsdfsdf')
                    ->htmlTemplate('email.html.twig')
                    ->context([
                        'expiration_date' => new \DateTime('+7 days'),
                        'username' => $username,
                    ])
                ;
                $mailer->send($email);{}
                $rating = new Review();
                $rating->setIdUser($idUser->getIdUser());
                $rating->setPlaceName($place->getPlaceName());
                $rating->setReviewDate(new DateTime());
                $rating->setPlace($place);
                $rating->setReviewTxt($reviewTxt);
                $rating->setRating($ratedIndex);
                $entityManager->persist($rating);
                }
                // var_dump($rating);

                if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
                    $review = $reviewForm->getData();
                    $review->setReviewTxt($reviewTxt); 
                    $entityManager->persist($review);
                }
                // var_dump($rating);

                $entityManager->flush();
            }
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode(['idUser' => $idUser, 'reviewTxt' => $reviewTxt]));
            return $response;
        }
        return $this->render('rating/rating.html.twig', [
            'idUser' => $idUser,
            'username' => $username,
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
