<?php


namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Image;

class ImageFixture extends BaseFixture implements DependentFixtureInterface
{
    private $images;

    public function __construct()
    {
        $this->images = [
            [
                "filename" => "backside-triple-cork",
                "extension" => "jpg",
                "height" => "600",
                "width" => "800",
                "alt" => "Une photographie d'une figure de snowboard",
                "title" => "Backside Triple Cork 1440",
                "trick" => "Trick-0"
            ],
            [
                "filename" => "method-air",
                "extension" => "jpg",
                "height" => "600",
                "width" => "800",
                "alt" => "Une photographie d'une figure de snowboard",
                "title" => "Method Air",
                "trick" => "Trick-1"
            ],
            [
                "filename" => "double-backflip-one-foot",
                "extension" => "jpg",
                "height" => "600",
                "width" => "800",
                "alt" => "Une photographie d'une figure de snowboard",
                "title" => "Double Backflip One Foot",
                "trick" => "Trick-2"
            ],
            [
                "filename" => "double-mc-twist",
                "extension" => "jpg",
                "height" => "600",
                "width" => "800",
                "alt" => "Une photographie d'une figure de snowboard",
                "title" => "Double McTwist 1260",
                "trick" => "Trick-3"
            ],
            [
                "filename" => "double-backside-rodeo",
                "extension" => "jpg",
                "height" => "600",
                "width" => "800",
                "alt" => "Une photographie d'une figure de snowboard",
                "title" => "Double Backside Rodeo 1080",
                "trick" => "Trick-4"
            ]
        ];
    }

    public function load(ObjectManager $manager)
    {
        //Attention, avec cette méthode, le nombre d'images doit être le même que le nombre de figures !
        foreach($this->images as $index => $imageData)
        {
            $image = new Image();
            $trick = $this->getReference($imageData["trick"]);
            $image->setFilename($imageData["filename"]);
            $image->setExtension($imageData["extension"]);
            $image->setTrick($trick);
            $image->setHeight($imageData["height"]);
            $image->setWidth($imageData["width"]);
            $image->setAlt($imageData["alt"]);
            $image->setTitle($imageData["title"]);
            $image->setCreatedAt(new \DateTime());
            $this->addReference("Image-".$index, $image);

            $manager->persist($image);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixture::class
        ];
    }
}