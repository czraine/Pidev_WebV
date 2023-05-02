<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Reservationcircuit;
use App\Form\ReservationcircuitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Twilio\Rest\Client;

use Stripe\Checkout\Session;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/reservationcircuit')]
class ReservationcircuitController extends AbstractController
{
    #[Route('/', name: 'app_reservationcircuit_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservationcircuits = $entityManager
            ->getRepository(Reservationcircuit::class)
            ->findAll();
        return $this->render('front/reservation.html.twig', [
            'reservationcircuits' => $reservationcircuits,
        ]);
    }

    #[Route('/new', name: 'app_reservationcircuit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationcircuit = new Reservationcircuit();
        $form = $this->createForm(ReservationcircuitType::class, $reservationcircuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationcircuit);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationcircuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservationcircuit/new.html.twig', [
            'reservationcircuit' => $reservationcircuit,
            'form' => $form,
        ]);
    }

    #[Route('/{numRes}', name: 'app_reservationcircuit_show', methods: ['GET'])]
    public function show(Reservationcircuit $reservationcircuit): Response
    {
        return $this->render('reservationcircuit/show.html.twig', [
            'reservationcircuit' => $reservationcircuit,
        ]);
    }

    #[Route('/{numRes}/edit', name: 'app_reservationcircuit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservationcircuit $reservationcircuit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationcircuitType::class, $reservationcircuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationcircuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservationcircuit/edit.html.twig', [
            'reservationcircuit' => $reservationcircuit,
            'form' => $form,
        ]);
    }

    #[Route('/{numRes}', name: 'app_reservationcircuit_delete', methods: ['POST'])]
    public function delete(Request $request, Reservationcircuit $reservationcircuit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationcircuit->getNumRes(), $request->request->get('_token'))) {
            $entityManager->remove($reservationcircuit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationcircuit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/checkout/{numRes}', name: 'app_reservationcircuit_payment' , methods : ["POST" , "GET"])]
    public function checkout(Request $request, Reservationcircuit $reservationcircuit): Response
    {


        Stripe::setApiKey('sk_test_51KSORWJTNlYsf9D6QoJ9FZoKq7NVMJ2ybI3NMrbpa2L6h1EBLbzy01mZD3bmtvDkN8ZjNOsn5JYfTxezdaxfL0Lh00b7VePMWg');
       
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $reservationcircuit->getNc()->getNom(),
                        ],
                        'unit_amount'  => $reservationcircuit->getNc()->getPrix() *100  ,
                    ],
                    'quantity'   => $reservationcircuit->getNbrPlace(),
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [
                "numRes"=>$reservationcircuit->getNumRes()
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);

    }
    #[Route('/success-url/res/{numRes}', name: 'success_url')]
    public function successUrl(MailerInterface $mailer , Reservationcircuit $reservationcircuit, EntityManagerInterface $entityManager): Response
    {
        $reservationcircuit->setIsPaid(true);
        $entityManager->persist($reservationcircuit);
        $entityManager->flush();

        $circuit=$entityManager
        ->getRepository(Circuit::class)
        ->find($reservationcircuit->getNc());
        $circuit->setNbPlace($circuit->getNbPlace()-$reservationcircuit->getNbrPlace());
        $entityManager->persist($circuit);
        $entityManager->flush();




        $mail=(new TemplatedEmail())->from(new Address("roadreveltrip@gmail.com"))->
        to("rebhi.arwa@esprit.tn")  
        ->subject("ee")->htmlTemplate(
            'front/email.html.twig'
        )->context([
            'reservationcircuit' => $reservationcircuit,
        
        ]);

        $mailer->send($mail) ; 



       
        $sid    = "AC390b5247be71bb74a77077114d92888f";
        $token  = "1acd22356192c075840fc854cd151bbe";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
          ->create("+21623875468", 
            array(
              "from" => "+19705162714",
              "body" => "We're excited to inform you that your payment for your trip reservation has been successfully processed. Your transaction has been approved, and we have received your payment. We're thrilled to have you as our customer and look forward to serving you soon.\n For more details do not hesitate to  contact us :  roadrevel@esprit.tn"
            )
          );
          print($message->sid);

       
          



        return $this->redirectToRoute('app_reservationcircuit_index', [], Response::HTTP_SEE_OTHER);    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('front/reservation.html.twig', []);
    }
}
