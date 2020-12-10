<?php

namespace App\DataFixtures;

use App\Entity\Loan;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoanFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $loan = new Loan();
        $loan
            ->setName("Prêt d'outil à Kl3sk")
            ->setDescription("Prêt de Product 1")
            ->addProduct($manager->getRepository(Product::class)->findOneBy(["name" => "Product 1"]))
            ->addProduct($manager->getRepository(Product::class)->findOneBy(["name" => "Product 2"]))
            ->setLoanedBy($manager->getRepository(User::class)->findOneBy(["name" => 'admin']))
            ->setBorrowedBy($manager->getRepository(User::class)->findOneBy(["name" => 'user']))
            ->setLoanedAt(new \DateTime());
        $manager->persist($loan);
        $manager->flush();
    }

    public function getOrder() {
        return 5;
    }
}
