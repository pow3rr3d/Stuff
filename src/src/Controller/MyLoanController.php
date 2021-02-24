<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanArchive;
use App\Entity\Product;
use App\Form\LoanType;
use App\Form\MyLoanedStuffsSearchType;
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

        $search = new Loan();
        $form = $this->createForm(MyLoanedStuffsSearchType::class, $search);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $this->getDoctrine()->getManager()->getRepository(Loan::class)->getAllLoansQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('myLoans/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
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