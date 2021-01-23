<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $form->get('password')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($password);
            $user->setRoles("ROLE_USER");
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User '. $user->getName() .' created with successful! ');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $password = $encoder->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($form->get('password')->getData());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'User '. $user->getName() .' edited with successful! ');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash('success', 'User '. $user->getName() .' deleted with successful! ');
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/admin/{id}", name="user_admin", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function setAdmin(Request $request, User $user, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('update' . $user->getId(), $request->request->get('_token'))) {
            $user->setRoles("ROLE_ADMIN");
            $em->flush();
        }
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/user/{id}", name="user_user", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function setUser(Request $request, User $user, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('update' . $user->getId(), $request->request->get('_token'))) {
            $user->setRoles("ROLE_USER");
            $em->flush();
        }
        return $this->redirectToRoute('user_index');
    }
}
