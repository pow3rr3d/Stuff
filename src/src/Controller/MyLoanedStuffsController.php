<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanArchive;
use App\Entity\Product;
use App\Form\LoanType;
use App\Form\ReturnLoanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/myloanedstuffs")
 */
class MyLoanedStuffsController extends AbstractController
{
    /**
     * @Route("/", name="myloanedstuffs_index", methods={"GET","POST"})
     * @return Response
     */
    public function index(Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("My Loaned Stuffs", $this->get("router")->generate("myloanedstuffs_index"));

        return $this->render('myLoanedStuffs/index.html.twig', [
            'user' => $this->getUser(),
        ]);

    }

    /**
     * @Route("/new", name="myloanedstuffs_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("My Loaned Stuffs", $this->get("router")->generate("myloanedstuffs_index"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("myloanedstuffs_new"));

        $entityManager = $this->getDoctrine()->getManager();
        $loan = new Loan();
        $form = $this->createForm(LoanType::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $loan->setLoanedBy($this->getUser());
            foreach ($form->getData()->getProduct() as $product) {
                $product->setLoan($form->getData());
            }
            $entityManager->persist($loan);
            $entityManager->flush();

            return $this->redirectToRoute('myloanedstuffs_index');
        }

        return $this->render('myLoanedStuffs/new.html.twig', [
            'loan' => $loan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="myloanedstuffs_show", methods={"GET"})
     * @param Loan $loan
     * @return Response
     */
    public function show(Loan $loan, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("My Loaned Stuffs", $this->get("router")->generate("myloanedstuffs_index"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("myloanedstuffs_show",["id" => $loan->getId()]));

        return $this->render('myLoanedStuffs/show.html.twig', [
            'loan' => $loan,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="myloanedstuffs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Loan $loan, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("My Loaned Stuffs", $this->get("router")->generate("myloanedstuffs_index"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("myloanedstuffs_edit",["id" => $loan->getId()]));

        if ($loan->getReturnAt() !== null){
            return $this->redirectToRoute("myloanedstuffs_index");
        }
        $form = $this->createForm(LoanType::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData()->getProduct() as $product) {
                $product->setLoan($loan);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('myloanedstuffs_index');
        }

        return $this->render('myLoanedStuffs/edit.html.twig', [
            'loan' => $loan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="myloanedstuffs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Loan $loan, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $loan->getId(), $request->request->get('_token'))) {
            $products = $manager->getRepository(Product::class)->findBy(["loan" => $loan]);
            foreach ($products as $product) {
                $product->setLoan(null);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($loan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('myloanedstuffs_index');
    }

    /**
     * @Route("/{id}", name="myloanedstuffs_return", methods={"PUT", "POST"})
     */
    public function return(Request $request, Loan $loan, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("My Loaned Stuffs", $this->get("router")->generate("myloanedstuffs_index"));
        $breadcrumbs->addItem("Return", $this->get("router")->generate("myloanedstuffs_return",["id" => $loan->getId()]));

        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findBy(["loan" => $loan]);

        $prod = [];
        foreach ($products as $product) {
            $prod[$product->getId()] = $product->getState();
        }

        $form = $this->createForm(ReturnLoanType::class, $loan);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $arr = [];
            foreach ($form->getData()->getProduct() as $item) {
                $arr[$item->getId()] = $item->getState();
            }

            $diff = array_diff($prod, $arr);
            if ($diff !== []) {

                $entityManager->flush();

            } else {
                $archive = new LoanArchive();
                $archive->setLoan($loan);
                $products = $entityManager->getRepository(Product::class)->findBy(['loan' => $loan]);
                foreach ($products as $product) {
                    $archive->addProduct($product);
                    $product->setLoan(null);
                }
                $loan->setReturnAt(new \DateTime());
                $entityManager->persist($archive);
                $entityManager->flush();

            }


            return $this->redirectToRoute('myloanedstuffs_index');
        }
        

        return $this->render('myLoans/return.html.twig', [
            'form' => $form->createView(),
            "id" => $loan->getId()]);
    }
}