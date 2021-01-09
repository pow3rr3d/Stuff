<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Form\LoanType;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/myloans")
 */
class MyLoanController extends AbstractController
{
    /**
     * @Route("/", name="myloanedstuffs_index", methods={"GET","POST"})
     */
    public function index(Request $request, LoanRepository $loanRepository): Response
    {

        return $this->render('myLoanedStuffs/index.html.twig', [
            'user' => $this->getUser(),
        ]);

    }

    /**
     * @Route("/{id}", name="myloanedstuffs_show", methods={"GET"})
     */
    public function show(Request $request, Loan $loan, LoanRepository $loanRepository): Response
    {

        return $this->render('myLoanedStuffs/show.html.twig', [
            'loan' => $loan,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="myloanedstuffs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Loan $loan): Response
    {
        $form = $this->createForm(LoanType::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('myloanedstuffs_index');
        }

        return $this->render('myLoanedStuffs/edit.html.twig', [
            'product' => $loan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="myloanedstuffs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Loan $loan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $loan->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($loan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('myloanedstuffs_index');
    }
}