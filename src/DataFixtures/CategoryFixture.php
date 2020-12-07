<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends BaseFixture
{
    public $categories;

    public function __construct()
    {
        $this->categories = [
            ["name" => "Backflips"],
            ["name" => "Corks"],
            ["name" => "Airs"]
        ];
    }

  public function load(ObjectManager $manager)
  {

    foreach ($this->categories as $index => $categoryData)
    {
      $category = new Category();
      $category->setName($categoryData["name"]);
      $category->setCreatedAt(new \DateTime());
      $this->addReference("Category-".$index, $category);

      $manager->persist($category);
    }
    $manager->flush();
  }
}