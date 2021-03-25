<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Product::class);

        $this->user = $security->getUser();
    }

    public function getAllQuery(Product $search)
    {
        $qb = $this->createQueryBuilder('s');
        $qb ->where('s.user =:user')
            ->setParameter('user', $this->user);

        if ($search->getName() !== null) {
            $qb
                ->andWhere($qb->expr()->like('s.name' , ':name'))
                ->orWhere($qb->expr()->like('s.id' , ':name'))
                ->setParameter('name', '%'.$search->getName().'%');
        }

        return $qb->getQuery();
    }

    public function getAllAdminQuery(Product $search)
    {
        $qb = $this->createQueryBuilder('s');

        if ($search->getName() !== null) {
            $qb
                ->andWhere($qb->expr()->like('s.name' , ':name'))
                ->orWhere($qb->expr()->like('s.id' , ':name'))
                ->setParameter('name', '%'.$search->getName().'%');
        }

        return $qb->getQuery();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
