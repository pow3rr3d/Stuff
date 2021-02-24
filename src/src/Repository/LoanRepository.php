<?php

namespace App\Repository;

use App\Entity\Loan;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Loan::class);

        $this->user = $security->getUser();
    }

    public function getAllQuery(Loan $search)
    {
        $qb = $this->createQueryBuilder('s');
        $qb ->Where($qb->expr()->eq('s.loanedBy' , ':user'))
            ->setParameter('user', $this->user);

        if ($search->getName() !== null) {
            $qb
                ->leftJoin('s.borrowedBy', 'u')
                ->andWhere($qb->expr()->like('s.name' , ':name'))
                ->orWhere($qb->expr()->like('u.name' , ':name'))
                ->orWhere($qb->expr()->like('s.id' , ':name'))
                ->setParameter('name', '%'.$search->getName().'%');
        }

        return $qb->getQuery();
    }

    public function getAllLoansQuery(Loan $search)
    {
        $qb = $this->createQueryBuilder('s');
        $qb ->Where($qb->expr()->eq('s.borrowedBy' , ':user'))
            ->setParameter('user', $this->user);

        if ($search->getName() !== null) {
            $qb
                ->leftJoin('s.loanedBy', 'u')
                ->andWhere($qb->expr()->like('s.name' , ':name'))
                ->orWhere($qb->expr()->like('u.name' , ':name'))
                ->setParameter('name', '%'.$search->getName().'%');
        }

        return $qb->getQuery();
    }

    // /**
    //  * @return Loan[] Returns an array of Loan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loan
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
