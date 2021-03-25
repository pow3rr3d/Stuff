<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard_index")
     */
    public function index(Breadcrumbs $breadcrumbs, EntityManagerInterface $em)
    {
        $breadcrumbs->addItem("Dashboard", $this->get("router")->generate("dashboard_index"));

        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([
            'user' => $this->getUser(),
            ]);

//        $loanedProducts = $this->getDoctrine()->getRepository(Product::class)->findBy([
//            'user' => $this->getUser(),
//            'loan' =>
//            ]);


        $qb = $em->createQueryBuilder();
        $qb->select('s')
            ->from('App\Entity\Product', 's')
            ->where('s.user = :user')
            ->andWhere('s.loan != :null')
            ->setParameter('user', $this->getUser()->getId())
            ->setParameter('null', "null");

        $loanedProducts = $qb->getQuery()->getResult();

        $loans = $em->getRepository(Loan::class)->findBy([
            'loanedBy' => $this->getUser(),
            'returnAt' => null
        ]);


        return $this->render('dashboard/index.html.twig', [
            'products' => $products,
            'loanedProducts' => $loanedProducts,
            'loans' => $loans
        ]);
    }
}
