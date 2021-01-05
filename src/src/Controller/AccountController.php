<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/{id}", name="account_index", methods={"GET","POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $form->get('password')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Account for '. $user->getUsername() .' edited with successful! ');
            return $this->redirectToRoute('account_index', ["id" => $user->getId()]);
        }

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }
}