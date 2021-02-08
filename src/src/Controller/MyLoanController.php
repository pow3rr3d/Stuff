<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanArchive;
use App\Entity\Product;
use App\Form\LoanType;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/myloans")
 */
class MyLoanController extends AbstractController
{
    /**
     * @Route("/", name="myloans_index", methods={"GET","POST"})
     * @return Response
     */
    public function index(Breadcrumbs $breadcrumbs, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $breadcrumbs->addItem("My Loans", $this->get("router")->generate("myloans_index"));

        $qb = $em->createQueryBuilder();
        $qb->select('s')
            ->from('App\Entity\Loan', 's')
            ->where('s.borrowedBy = :user')
            ->setParameter('user', $this->getUser()->getId())
        ;
        $pagination = $paginator->paginate(
            $qb->getQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('myLoans/index.html.twig', [
            'pagination' => $pagination
        ]);

    }


    /**
     * @Route("/{id}", name="myloans_show", methods={"GET"})
     * @param Loan $loan
     * @return Response
     */
    public function show(Loan $loan, Breadcrumbs $breadcrumbs): Response
    {

        $breadcrumbs->addItem("My Loans", $this->get("router")->generate("myloans_index"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("myloans_show", ["id" => $loan->getId()]));

        return $this->render('myLoans/show.html.twig', [
            'loan' => $loan,
        ]);

    }
}