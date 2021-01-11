<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanArchive;
use App\Entity\Product;
use App\Form\LoanType;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/", name="myloans_index", methods={"GET","POST"})
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('myLoans/index.html.twig', [
            'user' => $this->getUser(),
        ]);

    }


    /**
     * @Route("/{id}", name="myloans_show", methods={"GET"})
     * @param Loan $loan
     * @return Response
     */
    public function show(Loan $loan): Response
    {

        return $this->render('myLoans/show.html.twig', [
            'loan' => $loan,
        ]);

    }
}