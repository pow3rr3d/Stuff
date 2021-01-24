<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $subcategories = $form->get('subcategories')->getData();
            foreach ($subcategories as $subcategory) {
                $category->addSubcategory($subcategory);
                $subcategory->setCategory($category);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sub = $this->getDoctrine()->getManager()->getRepository(Subcategory::class)->findBy(["category" => $category]);

            foreach ($sub as $s) {
                $oldSub[$s->getId()] = $s;
            }

            $subcategories = $form->get('subcategories')->getData();

            foreach ($subcategories as $subcategory) {
                $newSub[] = $subcategory;
            }
            if (!empty($oldSub)) {
                foreach ($oldSub as $o) {
                    $products = $this->getDoctrine()->getRepository(Product::class)->findBy(["subcategory" => $o]);
                    foreach ($products as $product){
                        $product->setSubcategory(null);
                    }
                    $category->removeSubcategory($o);
                    $this->getDoctrine()->getManager()->remove($o);

                }
            }
            if (!empty($newSub)) {
                foreach ($newSub as $subcategory) {
                    $category->addSubcategory($subcategory);
                    $subcategory->setCategory($category);
                }
            }


            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $sub = $this->getDoctrine()->getManager()->getRepository(Subcategory::class)->findBy(["category" => $category]);
            if (!empty($sub)) {
                foreach ($sub as $s) {
                    $products = $this->getDoctrine()->getRepository(Product::class)->findBy(["subcategory" => $s]);
                    foreach ($products as $product){
                        $product->setSubcategory(null);
                    }
                    $category->removeSubcategory($s);
                    $this->getDoctrine()->getManager()->remove($s);

                }
            }
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
