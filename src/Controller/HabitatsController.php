<?php

namespace App\Controller;

use App\Entity\Habitats;
use App\Form\HabitatsType;
use App\Form\HabitatsType_user;
use App\Repository\HabitatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ReservationsRepository;


use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/habitats')]
class HabitatsController extends AbstractController
{
    #[Route('/', name: 'app_habitats_index', methods: ['GET'])]

    public function index(HabitatsRepository $habitatsRepository, Security $user): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) { // Permet de rediriger l'user selon son premier rôle
            return $this->render('habitats/index.html.twig', [
                'habitats' => $habitatsRepository->findAll(),
            ]);
        } else
            return $this->render('habitats/user_index.html.twig', [
                'habitats' => $habitatsRepository->findBy(['proprio' => $this->getUser()->getId()]),
            ]);
    }

    #[Route('/new', name: 'app_habitats_new', methods: ['GET', 'POST'])]
    public function new (Request $request, HabitatsRepository $habitatsRepository, Security $user): Response
    {
        $user = $user->getUser();
        $user_role = $user->getRoles();

        if ($user_role[0] == 'ROLE_ADMIN') {
            $habitat = new Habitats();
            $form = $this->createForm(HabitatsType::class, $habitat);
            $form->handleRequest($request);
        } else {
            $habitat = new Habitats();
            $habitat->setProprio($this->getUser());
            $form = $this->createForm(HabitatsType_user::class, $habitat);
            $form->handleRequest($request);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $habitatsRepository->save($habitat, true);

            return $this->redirectToRoute('app_habitats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitats/new.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitats_show', methods: ['GET'])]
    public function show(Request $request, Habitats $habitat, ReservationsRepository $reservationsRepository): Response
    {
        // $reservation = new Reservations();
        // $form = $this->createForm(Reservations1Type_user::class, $reservation);
        // $form->handleRequest($request);

        // if ($form->isSubmitted()) {
        //     return $this->redirectToRoute('app_habitat_buy', [], Response::HTTP_SEE_OTHER);
        // }
        if ($this->isGranted('ROLE_ADMIN')) { // Permet de rediriger l'user selon son premier rôle
            return $this->render('habitats/show.html.twig', [
                'habitat' => $habitat,
            ]);
        } else {
            return $this->render('habitats/show_user.html.twig', [
                'habitat' => $habitat,
                // 'reservation' => $reservation,
                // 'form' => $form,
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'app_habitats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitats $habitat, HabitatsRepository $habitatsRepository, Security $user): Response
    {
        // Permet de renvoyer au role USER un form adapté à ses droits

        $user = $user->getUser();
        $user_role = $user->getRoles();

        if ($user_role[0] == 'ROLE_ADMIN') {

            $form = $this->createForm(HabitatsType::class, $habitat);
            $form->handleRequest($request);
        } else {
            $form = $this->createForm(HabitatsType_user::class, $habitat);
            $form->handleRequest($request);
        }

        //---------------------------------------------------

        if ($form->isSubmitted() && $form->isValid()) {
            $habitatsRepository->save($habitat, true);

            return $this->redirectToRoute('app_habitats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitats/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitats_delete', methods: ['POST'])]
    public function delete(Request $request, Habitats $habitat, HabitatsRepository $habitatsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $habitat->getId(), $request->request->get('_token'))) {
            $habitatsRepository->remove($habitat, true);
        }

        return $this->redirectToRoute('app_habitats_index', [], Response::HTTP_SEE_OTHER);
    }



    // Page de paiement stripe 

    #[Route('/{id}/buy', name: 'app_habitat_buy', methods: ['GET'])]
    public function buy(Habitats $habitat): Response
    {
        return $this->render('habitats/habitat_buy.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    #[Route('/intentPayment', name: 'app_paiement_stripe')]
    public function intentStripe(SerializerInterface $serializerInterface): JsonResponse
    {
        //Insérer la clé secrète pour relier votre clé public à la clé secret
        Stripe::setApiKey('sk_test_51Mf1j1FufBPCUONNJMWBzxMnyfHa5NdSycSU0Tclj0zPTktHfwIPaaEP4R3SwfBCgtpuE6o4aIpsPgu0F1vMOH6y00kbKWYWQF');

        header('Content-type : application/json');

        try {

            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            dump($jsonObj);

            //Créer l'intention de paiment avec le prix et le device
            $paymentIntent = PaymentIntent::create([
                'amount' => $jsonObj->items[0]->prix * 100,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'Paiement de ' . $jsonObj->items[0]->prenom . ' ' . $jsonObj->items[0]->nom
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return $this->json([
                'clientSecret' => $output['clientSecret']
            ]);


        } catch (Error $e) {
            http_response_code(500);
            echo json_decode(['error' => $e->getMessage()]);
        }

        return $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/confirmation-paiement', name: 'app_paiement_confirmation')]
    public function confirPaiment(Habitats $habitat, HabitatsRepository $habitatsRepository, ReservationsRepository $reservationsRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservations();
        $habitat = new Habitats();

        $habitatsRepository->save($habitat,true);

        $reservationsRepository->save($reservation,true);
        $reservationsRepository->getEntityManager()->persist($reservation);
        var_dump($reservation);
        return $this->render('paiement/confirmation.html.twig', [
        ]);
    }

}