<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class ModifierCompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte_show')]
    public function index(Security $user): Response
    {   $user = $user->getUser();
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'ModifierCompteController',
            'user' => $user
        ]);
    }

    #[Route('/edit', name: 'app_compte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Security $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {   $user =$user->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_compte_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compte/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
