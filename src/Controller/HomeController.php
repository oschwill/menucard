<?php

namespace App\Controller;

use App\Repository\MealsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $randomMeal = array();

    #[Route('/', name: 'home')]
    public function index(MealsRepository $mr): Response
    {
        $meals = $mr->findAll();
        $randomIndex = array_rand($meals, 2);

        for ($i=0; $i < count($randomIndex); $i++) { 
            array_push($this->randomMeal, $meals[$randomIndex[$i]]);            
        }

        return $this->render('home/index.html.twig', [
            'randomMeals' => $this->randomMeal
        ]);
    }
}
