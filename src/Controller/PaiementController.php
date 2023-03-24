<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

use Exception;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Sources;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement')]
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }

    #[Route('/intentPayment', name: 'app_paiement_stripe')]
    public function intentStripe(SerializerInterface $serializerInterface): JsonResponse
    {  
        //Insérer la clé secrète pour relier votre clé public à la clé secret
       Stripe::setApiKey('sk_test_51Mf1j1FufBPCUONNJMWBzxMnyfHa5NdSycSU0Tclj0zPTktHfwIPaaEP4R3SwfBCgtpuE6o4aIpsPgu0F1vMOH6y00kbKWYWQF');

        header('Content-type : application/json');

        try{

            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            dump($jsonObj);

            //Créer l'intention de paiment avec le prix et le device
            $paymentIntent = PaymentIntent::create([
                'amount' => $jsonObj->items[0]->prix*100,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'Paiement de '.$jsonObj->items[0]->prenom.' '.$jsonObj->items[0]->nom
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return $this->json([
                'clientSecret' => $output['clientSecret']
            ]);


        }catch(Error $e){
            http_response_code(500);
            echo json_decode(['error'=> $e->getMessage()]);
        }

        return $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/confirmation-paiement', name: 'app_paiement_confirmation')]
    public function confirPaiment(): Response
    { 
        return $this->render('paiement/confirmation.html.twig', [
        ]);

    }

}