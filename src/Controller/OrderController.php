<?php

namespace App\Controller;

use App\Entity\Meals;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/order', name: 'order')]
    public function index(OrderRepository $orderRep): Response
    {
        $orders = $orderRep->findBy(
            ['tisch' => 'tisch1']
        );

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/order/{id}', name: 'orderMeal')]
    public function order(Meals $meals, ManagerRegistry $doctrine)
    {
        $order = new Order();
        $order->setTisch("tisch1");
        $order->setName($meals->getName());
        $order->setBnummer($meals->getId());
        $order->setPrice($meals->getPrice());
        $order->setStatus("offen");

        // entityManager
        $em = $this->doctrine->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash('order', $order->getName() . ' wurde zur Bestellung hinzugefügt!');

        return $this->redirect($this->generateUrl('menu'));
    }

    #[Route('/status/{id}, {status}', name: 'status')]
    public function status($id, $status)
    {
        $em = $this->doctrine->getManager();
        // Wir suchen mit der übergebenen Id den Datensatz aus dem Order Repository
        $order = $em->getRepository(Order::class)->find($id); 

        // Wir setzten den neuen Status
        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
    }

    #[Route('/order/delete/{id}', name: 'deleteOrder')]
    public function delete($id, OrderRepository $or)
    {
        try {            
            // Datensatz löschen
            $em = $this->doctrine->getManager();
            $order = $or->find($id);
            $em->remove($order);
            $em->flush();

        } catch (\Throwable $th) {
            $this->addFlash('failedOrder', 'Die Bestellung konnte nicht gelöscht werden!');
        }
        
        // Weiterleitung
        return $this->redirect($this->generateUrl('order'));
    }
}
