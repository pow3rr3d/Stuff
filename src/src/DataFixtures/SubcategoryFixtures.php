<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class SubcategoryFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $batchSize = 10;

        for($i = 1; $i < 100; $i++)
        {
            $Subcategory = new Subcategory();
            $category =  $manager->getRepository(Category::class)->findOneBy(["name" => "Category {$i}"]);
            $Subcategory
                ->setName("Subcategory {$i}")
                ->setCategory($category);
            $manager->persist($Subcategory);

            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear();
            }
        }


    }

    public function getOrder() {
        return 3;
    }
}
