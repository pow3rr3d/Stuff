<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(Breadcrumbs $breadcrumbs, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $breadcrumbs->addItem("Categories", $this->get("router")->generate("category_index"));

        $search = new Category();
        $form = $this->createForm(CategorySearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(Category::class)->getAllQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Categories", $this->get("router")->generate("category_index"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("category_new"));

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
    public function show(Category $category, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Categories", $this->get("router")->generate("category_index"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("category_show", ["id" => $category->getId()]));

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Categories", $this->get("router")->generate("category_index"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("category_edit", ["id" => $category->getId()]));

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
