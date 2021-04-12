<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/index", name="search_index", methods={"POST"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function search(EntityManagerInterface $em)
    {
        $results[] = [
            "role" => [
                "value" => $this->getUser()->getRoles()
            ]
        ];

        $json = json_decode(file_get_contents('php://input'), true);

        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('App\Entity\User', 'u')
            ->orwhere($qb->expr()->like('u.name', ':data'))
            ->orWhere($qb->expr()->like('u.surname', ':data'))
            ->orWhere($qb->expr()->like('u.email', ':data'))
            ->setParameter('data', '%' . $json["search"]. '%')
            ->orderBy('u.id', 'ASC');
        if ($this->getUser()->getRoles() === ["ROLE_USER"]){
            $qb->andWhere($qb->expr()->eq('u.id', ":id"))
                ->setParameter("id", $this->getUser()->getId());
        }

        $users = $qb->getQuery()->getResult();

        foreach ($users as $user){
            $results[] = [
                "user" => [
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "surname" => $user->getSurname(),
                    "email" => $user->getEmail(),
                ]
            ];
        }

        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('App\Entity\Product', 'p')
            ->orwhere($qb->expr()->like('p.name', ':data'))
            ->orWhere($qb->expr()->like('p.description', ':data'))
            ->setParameter('data', '%' . $json["search"]. '%')
            ->orderBy('p.id', 'ASC');
        if ($this->getUser()->getRoles() === ["ROLE_USER"]){
            $qb->andWhere($qb->expr()->eq('p.user', ":id"))
                ->setParameter("id", $this->getUser()->getId());
        }

        $products = $qb->getQuery()->getResult();

        foreach ($products as $product){
            $results[] = [
                "product" => [
                    "id" => $product->getId(),
                    "name" => $product->getName(),
                    "description" => $product->getDescription(),
                ]
            ];
        }

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from('App\Entity\Subcategory', 'c')
            ->where($qb->expr()->like('c.name', ':data'))
            ->setParameter('data', '%' . $json["search"]. '%'
            )
            ->orderBy('c.id', 'ASC');

        $categories = $qb->getQuery()->getResult();

        foreach ($categories as $category){
            $results[] = [
                "category" => [
                    "id" => $category->getId(),
                    "name" => $category->getName(),
                ]
            ];
        }

        if (empty($results)){
            $results = 'No record find for '.$json["search"].'';
        }

        $response = new Response(json_encode($results, JSON_UNESCAPED_UNICODE));
        return $response;
    }

}