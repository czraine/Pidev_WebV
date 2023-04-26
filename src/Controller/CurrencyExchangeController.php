<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CurrencyExchangeController extends AbstractController
{
      

    /**
     * @Route("/currency/convert", name="currency_convert")
     */
    public function index(Request $request)
{
    $form = $this->createFormBuilder()
        ->add('amount', TextType::class)
        ->add('from_currency', TextType::class)
        ->add('to_currency', TextType::class)
        ->add('submit', SubmitType::class, ['label' => 'Convert'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $amount = $data['amount'];
        $fromCurrency = $data['from_currency'];
        $toCurrency = $data['to_currency'];

        $url = sprintf('https://api.apilayer.com/exchangerates_data/convert?from=%s&to=%s&amount=%s', $fromCurrency, $toCurrency, $amount);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apikey:VQZ62qtrLk5jSBOvy6u2wx4eU0ZL4412'));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $this->render('currency_exchange/result.html.twig', [
            'amount' => $amount,
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'result' => $result['result'],
        ]);
    }

    return $this->render('currency_exchange/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
