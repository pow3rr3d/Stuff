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
        for($i = 1; $i < 12; $i++)
        {
            $category = new Category();
            $category
                ->setName("Category {$i}");
            $manager->persist($category);
        }
        
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
}
