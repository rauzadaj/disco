<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use Doctrine\DBAL\Exception;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * Creation d'un utilisateur avec un formulaire
     * @param Request $request
     * @return Response
     */
    #[Route('/users/add', name: "add_user")]
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user->setActivated(0);
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('/users/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
