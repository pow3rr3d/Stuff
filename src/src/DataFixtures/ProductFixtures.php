<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Subcategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ProductFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i < 10; $i++)
        {
            $product = new Product();
            $Subcategory = $manager->getRepository(Subcategory::class)->findBy(["name" => "Subcategory {$i}"]);
            $product
                ->setName("Product {$i}")
                ->setDescription("Ceci est la description du produit {$i}. Il s'agit d'un super produit.")
                ->setSubcategory($Subcategory['0']);
            $manager->flush();

            $manager->persist($product);
        }

        $manager->flush();

    }

    public function getOrder() {
        return 4;
    }
}