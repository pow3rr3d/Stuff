<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductSearchType;
use App\Form\ProductType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


/**
 * @Route("/stuff")
 */
class StuffController extends AbstractController
{
    /**
     * @Route("/", name="stuff_index", methods={"GET"})
     */
    public function index(Breadcrumbs $breadcrumbs, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $breadcrumbs->addItem("Stuff", $this->get("router")->generate("stuff_index"));

        $search = new Product();
        $form = $this->createForm(ProductSearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(Product::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('stuff/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="stuff_new", methods={"GET","POST"})
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Stuff", $this->get("router")->generate("stuff_index"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("stuff_new"));

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
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
    public function show(Product $product, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Stuff", $this->get("router")->generate("stuff_index"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("stuff_show", ["id" => $product->getId()]));

        return $this->render('stuff/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stuff_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Stuff", $this->get("router")->generate("stuff_index"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("stuff_edit", ["id" => $product->getId()]));

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stuff_index');
        }

        return $this->render('stuff/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stuff_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stuff_index');
    }
}
