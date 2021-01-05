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

        for($i = 1; $i < 12; $i++)
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

    public function getOrder() {
        return 3;
    }
}
