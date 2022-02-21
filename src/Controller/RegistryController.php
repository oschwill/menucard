<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistryController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    #[Route('/registry', name: 'registry')]
    public function registry(Request $request, UserPasswordHasherInterface $passHasher): Response
    {
        // Wir bauen uns das Registrierungsformular
        $registryForm = $this->createFormBuilder()
        ->add('email', EmailType::class, [
            'label' => 'Mitarbeiter Email'
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Passwort'],
            'second_options' => ['label' => 'Passwort wiederholen'],
        ])
        ->add('registrieren', SubmitType::class)
        ->getForm();

        // Wir holen uns die eingegebenen Daten aus dem Formular
        $registryForm->handleRequest($request);

        // Wenn das Formular submitted wurde
        if ($registryForm->isSubmitted()) {
            $data = $registryForm->getData();

            $user = new User();
            $user->setEmail($data['email']);

            $user->setPassword(
                $passHasher->hashPassword($user, $data['password'])
            );

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('registry/index.html.twig', [
            'registryform' => $registryForm->createView()
        ]);
    }
}
