<?php

namespace App\Controller;

use App\Entity\Meals;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/order/{id}', name: 'order')]
    public function order(Meals $meals)
    {
        $order = new Order();
        $order->setTisch("tisch1");
        $order->setName($meals->getName());
        $order->setBnummer($meals->getId());
        $order->setPrice($meals->getPrice());
        $order->setStatus("offen");

        
    }
}
