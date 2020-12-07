<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Category;
use App\Services\Slugger;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixture extends BaseFixture implements DependentFixtureInterface
{
    private $tricks;
    private $slugger;

    public function __construct()
    {
        $this->slugger = new Slugger();
        $this->tricks = [[
            "name" => "Backside Triple Cork 1440",
            "description" => "En langage snowboard, un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut. Le tout premier Triple Cork a été plaqué par Mark McMorris en 2011, lequel a récidivé lors des Winter X Games 2012... avant de se faire voler la vedette par Torstein Horgmo, lors de ce même championnat. Le Norvégien réalisa son propre Backside Triple Cork 1440 et obtint la note parfaite de 50/50.",
            "category" => "Category-1",
        ],[
            "name" => "Method Air",
            "description" => "Cette figure – qui consiste à attraper sa planche d'une main et le tourner perpendiculairement au sol – est un classique \"old school\". Il n'empêche qu'il est indémodable, avec de vrais ambassadeurs comme Jamie Lynn ou la star Terje Haakonsen. En 2007, ce dernier a même battu le record du monde du \"air\" le plus haut en s'élevant à 9,8 mètres au-dessus du kick (sommet d'un mur d'une rampe ou autre structure de saut).",
            "category" => "Category-2",
        ],[
            "name" => "Double Backflip One Foot",
            "description" => "Comme on peut le deviner, les \"one foot\" sont des figures réalisées avec un pied décroché de la fixation. Pendant le saut, le snowboarder doit tendre la jambe du côté dudit pied. L'esthète Scotty Vine – une sorte de Danny MacAskill du snowboard – en a réalisé un bel exemple avec son Double Backflip One Foot.",
            "category" => "Category-0",
        ],[
            "name" => "Double Mc Twist 1260",
            "description" => "Le Mc Twist est un flip (rotation verticale) agrémenté d'une vrille. Un saut très périlleux réservé aux professionnels. Le champion précoce Shaun White s'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010. Nul doute que c'est cette figure qui lui a valu de remporter la médaille d'or.",
            "category" => "Category-1",
        ],[
            "name" => "Double Backside Rodeo 1080",
            "description" => "À l'instar du cork, le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé. Un des plus beaux de l'histoire est sans aucun doute le Double Backside Rodeo 1080 effectué pour la première fois en compétition par le jeune prodige Travis Rice, lors du Icer Air 2007. La pirouette est tellement culte qu'elle a fini dans un jeu vidéo où Travis Rice est l'un des personnages.",
            "category" => "Category-0",
        ]];
    }

  public function load(ObjectManager $manager)
  {
    foreach ($this->tricks as $index => $trickData)
    {
        $trick = new Trick();
        $category = $this->getReference($trickData["category"]);
        $user = $this->getReference("User-".rand(0, parent::MAX_USER_INDEX));
        $trick->setName($trickData["name"]);
        $trick->setSlug($this->slugger->slug($trickData["name"]));
        $trick->setDescription($trickData["description"]);
        $trick->setCategory($category);
        $trick->setUser($user);
        $trick->setCreatedAt(new \DateTime());
        $this->addReference("Trick-".$index, $trick);

        $manager->persist($trick);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
        UserFixture::class,
        CategoryFixture::class
    ];
  }
}