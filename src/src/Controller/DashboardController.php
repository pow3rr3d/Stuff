<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard_index")
     */
    public function index(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem("Dashboard", $this->get("router")->generate("dashboard_index"));
        return $this->render('dashboard/index.html.twig');
    }
}
