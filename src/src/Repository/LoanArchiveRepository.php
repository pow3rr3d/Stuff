<?php

namespace App\Repository;

use App\Entity\LoanArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoanArchive|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoanArchive|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoanArchive[]    findAll()
 * @method LoanArchive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanArchiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoanArchive::class);
    }

    // /**
    //  * @return LoanArchive[] Returns an array of LoanArchive objects
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
    public function findOneBySomeField($value): ?LoanArchive
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
