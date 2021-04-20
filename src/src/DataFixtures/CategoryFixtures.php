<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $batchSize = 10;


        for($i = 1; $i < 100; $i++)
        {
            $category = new Category();
            $category
                ->setName("Category {$i}");
            $manager->persist($category);

            if (($i % $batchSize) === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

    }

    public function getOrder() {
        return 2;
    }
}
