<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductsValidationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stuff")
 */
class StuffController extends AbstractController
{
    /**
     * @Route("/", name="stuff_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('stuff/index.html.twig', [
            'products' => $productRepository->findBy(["user" => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="stuff_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductsValidationType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $product->setUser($this->getUser());
            $product->setLoan(null);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('stuff_index');
        }

        return $this->render('stuff/new.html.twig', [
            'stuff' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stuff_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('stuff/show.html.twig', [
            'stuff' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stuff_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductsValidationType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stuff_index');
        }

        return $this->render('stuff/edit.html.twig', [
            'stuff' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stuff_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stuff_index');
    }
}
