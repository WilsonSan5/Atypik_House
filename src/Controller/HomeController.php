<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\HabitatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HabitatsRepository $habitatsRepository, CategoriesRepository $categoriesRepository ): Response
    {
        return $this->render('home/index.html.twig', [
           
            'categories' => $categoriesRepository->findAll(),
            'habitats_1' => $habitatsRepository->findBy(['categorie'=> 1],['id'=>'DESC']),
            'habitats_2' => $habitatsRepository->findBy(['categorie'=> 2],['id'=>'DESC']),
            'habitats_3' => $habitatsRepository->findBy(['categorie'=> 3],['id'=>'DESC']),
            'habitats_4' => $habitatsRepository->findBy(['categorie'=> 4],['id'=>'DESC']),
        ]);
    }
}
