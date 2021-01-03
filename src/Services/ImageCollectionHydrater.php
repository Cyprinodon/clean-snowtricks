<?php


namespace App\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use \DateTime;

class ImageCollectionHydrater
{
    private $uploader;
    private $entityManager;
    private $imageDirectory;

    public function __construct(Uploader $uploader, EntityManagerInterface $entityManager, string $imageDirectory)
    {
        $this->uploader = $uploader;
        $this->entityManager = $entityManager;
        $this->imageDirectory = $imageDirectory;
    }

    public function edit(FormInterface $form, PersistentCollection $imageCollection)
    {
        $imagesWitness = new ArrayCollection();

        foreach($imageCollection as $image)
        {
            $imagesWitness->add($image);
        }

        // Assurer l'intégrité de la base de données.
        foreach($form as $imageForm)
        {
            $image = $imageForm->getData();
            foreach($imagesWitness as $originalImage)
            {
                // Si l'image a été retirée de l'entité, trouver l'entité image et la supprimer.
                if(!$imageCollection->contains($originalImage))
                {
                    $this->entityManager->remove($originalImage);
                    $path = $this->imageDirectory.'/'
                        .$originalImage->getFilename().'.'.$originalImage->getExtension();
                    $this->uploader->remove($path);
                }
            }

            $imageFile = $imageForm->get('file')->getData();
            if($imageFile)
            {
                $fileInfo = $this->uploader->upload($imageFile);

                $image->setExtension($fileInfo['extension']);
                $image->setFilename($fileInfo['filename']);
                $image->setCreatedAt(new DateTime());
            }
        }
    }
}