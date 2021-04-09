<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Subcategory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ProductFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $batchSize = 10;

        for($i = 1; $i < 100000; $i++)
        {
            $product = new Product();
            $Subcategory = $manager->getRepository(Subcategory::class)->findOneBy(["name" => "Subcategory {$i}"]);
            $user = $manager->getRepository(User::class)->findBy(["name" => "admin"]);

            $product
                ->setName("Product {$i}")
                ->setUser($user['0'])
                ->setDescription("Ceci est la description du produit {$i}. Il s'agit d'un super produit.")
                ->setSubcategory($Subcategory);

            $manager->persist($product);

            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

    }

    public function getOrder() {
        return 4;
    }
}
