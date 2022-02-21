<?php

namespace App\Controller;

use App\Entity\Meals;
use App\Form\MealType;
use App\ImageOptimizer;
use App\Repository\MealsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/meals', name: 'meals.')] // der meals. dient als präfix für alle weiteren Routen dieses Controllers!
class MealsController extends AbstractController
{
    private ImageOptimizer $imageOptimizer;
    

    public function __construct(ImageOptimizer $imageOptimizer)
    {        
        $this->imageOptimizer = $imageOptimizer;
    }

    #[Route('/', name: 'edit')] 
    public function index(MealsRepository $mealsRep): Response
    {
        $meals = $mealsRep->findAll();
        return $this->render('meals/index.html.twig', [
            'meals' => $meals,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {        
        $meal = new Meals();
        /* Wir erstellen nun unser Formular */
        $form = $this->createForm(MealType::class, $meal); // MealType unser Formular, $meal unsere Entity
        // alle übermittelten Daten der Form können wir nun speichern
        $form->handleRequest($request);

        if ($form->isSubmitted()) { // Wenn der submit Button gedrückt wird
            // dump($request);
            // exit();
            // EntityManager
            $em = $doctrine->getManager();
            // Bild holen => anhang ist der gemappte name
            $picture = $form->get('anhang')->getData();

            if ($picture) {
                $filename = md5(uniqid()). '.' . $picture->guessClientExtension();
            }


            // Wir moven das Bild in unseren Ordner public/pictures
            $picture->move(
                // pictures_folder haben wir in der service.yaml unter parameter angelegt
                $this->getParameter('pictures_folder'),
                $filename
            );

            // image resizen??
            // $this->imageOptimizer->resize($this->getParameter('pictures_folder').$filename);

            $meal->setPicture($filename);

            $em->persist($meal);
            $em->flush();            

            // Weiterleitung
            return $this->redirect($this->generateUrl('meals.edit'));
        }

        // Response
        return $this->render('meals/create.html.twig', [
            'createForm' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, MealsRepository $mr, ManagerRegistry $doctrine)
    {
        try {            
            // Datensatz löschen
            $em = $doctrine->getManager();
            $meal = $mr->find($id);
            $em->remove($meal);
            $em->flush();
            
            // Datei löschen
            $filesystem = new Filesystem();
            $path = $this->getParameter('pictures_folder');            
            if ($filesystem->exists($path)) {
                $filesystem->remove($path.$meal->getPicture());
                // Flashmessages => die erscheint wenn ein Gericht gelöscht wird
                $this->addFlash('success', 'Das Gericht wurde erfolgreich entfernt!');
            }
        } catch (\Throwable $th) {
            $this->addFlash('failed', 'Das Gericht konnte nicht gelöscht werden!');
        }
        
        // Weiterleitung
        return $this->redirect($this->generateUrl('meals.edit'));
    }

    #[Route('/showmeal/{id}', name: 'showmeal')]
    public function showMeal(Meals $meals): Response
    {
        dump($meals);

        return $this->render('meals/showmeal.html.twig', [
            'meal' => $meals
        ]);
    }
}
