<?php

namespace App\Controller;

use App\Entity\Preference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/preferences")
 */
class PreferencesController extends AbstractController
{

/**
     * @Route("/darkmode", name="user_darkmode", methods={"POST"})
     */
    public function darkmode(EntityManagerInterface $em)
    {
        $json = json_decode( file_get_contents( 'php://input' ), true );
        $m = $em->getRepository(Preference::class)->findBy(['user' => $json['id']]);
        if(!$m)
        {
            $m = new Preference;
            $m->setUser($this->getUser());
            $m->setDarkmode($json['darkmode']);
            $em->persist($m);
            $em->flush();

        }
        else
        {
        $m[0]->setDarkmode($json['darkmode']);
        $em->persist($m[0]);
        $em->flush();
        }
        $response = new Response();
        $response->getStatusCode();
        return $response;
    }

}