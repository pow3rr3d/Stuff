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
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/{id}", name="account_index", methods={"GET","POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, User $user, Breadcrumbs $breadcrumbs): Response
    {
        if ($this->getUser()->getId() !== $user->getId()) {
            return $this->redirectToRoute("dashboard_index");
        }

        $breadcrumbs->addItem("My Account", $this->get("router")->generate("account_index", ["id" => $user->getId()]));

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($form->get('password')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Account for ' . $user->getUsername() . ' edited with successful! ');
            return $this->redirectToRoute('account_index', ["id" => $user->getId()]);
        }

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }
}