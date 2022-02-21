<?php

namespace App\Controller;

use App\Repository\MealsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function menu(MealsRepository $mr): Response
    {
        $meals = $mr->findAll();

        return $this->render('menu/index.html.twig', [
            'meals' => $meals
        ]);
    }
}
