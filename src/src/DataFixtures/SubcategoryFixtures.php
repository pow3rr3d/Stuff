<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubcategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        for($i = 1; $i < 10; $i++)
        {
            $Subcategory = new Subcategory();
            $category =  $manager->getRepository(Category::class)->findBy(["name" => "Category {$i}"]);
            $Subcategory
                ->setName("Subcategory {$i}")
                ->setCategory($category['0']);
            $manager->persist($Subcategory);
        }

        $manager->flush();
    }
}
