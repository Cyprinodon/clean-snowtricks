<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use App\Entity\Trick;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MessageFixture extends BaseFixture implements DependentFixtureInterface
{
    private $messages;

    public function __construct()
    {
        $this->messages = [
            ["content" => "Salut à tous. Je viens tout juste de m'inscrire pour dire que j'adore cette figure. Elle est vraiment impressionnante !"],
            ["content" => "Trop Bien."],
            ["content" => "Cool !!!"],
            ["content" => "Meh, je fais la même chose les yeux bandés."],
            ["content" => "C'est beau la neige."],
            ["content" => "Quelqu'un a déjà essayé ça ?"]
        ];
    }

  public function load(ObjectManager $manager)
  {

    //Création d'un utilisateur
    foreach($this->messages as $index => $messageData)
    {
      $randomUser = $this->getReference("User-".rand(0, parent::MAX_USER_INDEX));
      $randomTrick = $this->getReference("Trick-".rand(0, parent::MAX_TRICK_INDEX));

      $message = new Message();
      $message->setUser($randomUser);
      $message->setTrick($randomTrick);
      $message->setcontent($messageData["content"]);
      $message->setCreatedAt(new \DateTime());
      $this->addReference("Message-".$index, $message);

      $manager->persist($message);
    }
    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      UserFixture::class,
      TrickFixture::class
    ];
  }


}