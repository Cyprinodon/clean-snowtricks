<?php


namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use \DateTime;

class VideoCollectionHydrater
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function edit(FormInterface $form, PersistentCollection $videoCollection)
    {
        $videosWitness = new ArrayCollection();

        foreach($videoCollection as $video)
        {
            $videosWitness->add($video);
        }

        foreach($form as $videoForm) {
            $video = $videoForm->getData();
            foreach($videosWitness as $originalVideo)
            {
                if(!$videoCollection->contains($originalVideo))
                {
                    $this->entityManager->remove($originalVideo);
                }
            }
            $video->setAddedAt(new DateTime());
        }
    }
}