<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Form\ShoppingCartType;
use App\Repository\ProduitRepository;
use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Stripe;
use Stripe\Charge;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/cart", name="shopping_cart")
 */
class ShoppingCartController extends AbstractController
{
    #[Route('/', name: 'app_shopping_cart_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $shoppingCarts = $entityManager
            ->getRepository(ShoppingCart::class)
            ->findAll();

        return $this->render('shopping_cart/index.html.twig', [
            'shopping_carts' => $shoppingCarts,
        ]);
    }

    #[Route('/new', name: 'app_shopping_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shoppingCart = new ShoppingCart();
        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shoppingCart);
            $entityManager->flush();

            return $this->redirectToRoute('app_shopping_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shopping_cart/new.html.twig', [
            'shopping_cart' => $shoppingCart,
            'form' => $form,
        ]);
    }

    #[Route('/{idCart}', name: 'app_shopping_cart_show', methods: ['GET'])]
    public function show(ShoppingCart $shoppingCart): Response
    {
        return $this->render('shopping_cart/show.html.twig', [
            'shopping_cart' => $shoppingCart,
        ]);
    }

    #[Route('/{idCart}/edit', name: 'app_shopping_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShoppingCart $shoppingCart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_shopping_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shopping_cart/edit.html.twig', [
            'shopping_cart' => $shoppingCart,
            'form' => $form,
        ]);
    }

    #[Route('/{idCart}', name: 'app_shopping_cart_delete', methods: ['POST'])]
    public function delete(Request $request, ShoppingCart $shoppingCart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shoppingCart->getIdCart(), $request->request->get('_token'))) {
            $entityManager->remove($shoppingCart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shopping_cart_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idCart}/thankyou', name: 'app_products_success', methods: ['GET'])]
    public function success($idCart, ShoppingCartRepository $shoppingRepository): Response {

        return $this->render('shopping_cart/success.html.twig',['shoppingcarts' =>  $shoppingRepository->find($idCart),]);
    }

    #[Route('/stripe/stripe', name: 'app_stripe' , methods: 'GET')]
    public function stripeindex(Request $request): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_PUBLIC_KEY"],
        ]);
    }

    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['GET', 'POST'])]
    public function createCharge(Request $request)
    {
        try {
            Stripe\Stripe::setApiKey($_ENV['STRIPE_PRIVATE_KEY']);
            Stripe\Charge::create([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test",
            ]);
            $this->addFlash(
                'success',
                'Payment Successful!'
            );
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\CardException $e) {
            // Handle CardException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\RateLimitException $e) {
            // Handle RateLimitException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\InvalidRequestException $e) {
            // Handle InvalidRequestException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\AuthenticationException $e) {
            // Handle AuthenticationException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\ApiConnectionException $e) {
            // Handle ApiConnectionException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);

        } catch (Stripe\Exception\ApiErrorException $e) {
            // Handle ApiErrorException errors
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('shopping_cartapp_stripe', [], Response::HTTP_SEE_OTHER);
        }
    }




}
