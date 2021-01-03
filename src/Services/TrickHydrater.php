<?php


namespace App\Services;


use App\Entity\Trick;
use \DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\User;

class TrickHydrater
{
    private $slugger;
    private $uploader;
    private $imageHydrater;
    private $videoHydrater;
    private $entityManager;

    public function __construct(
        Slugger $slugger,
        Uploader $uploader,
        ImageCollectionHydrater $imageHydrater,
        VideoCollectionHydrater $videoHydrater,
        EntityManagerInterface $entityManager)
    {
        $this->slugger = $slugger;
        $this->uploader = $uploader;
        $this->imageHydrater = $imageHydrater;
        $this->videoHydrater = $videoHydrater;
        $this->entityManager = $entityManager;
    }

    //Hydrate un nouvel objet Trick en s'assurant que les images sont correctement enregistrées
    public function create(FormInterface $form, Trick $trick, User $user)
    {
        $url = $this->slugger->slug($trick->getName());

        $trick->setName($form->get('name')->getData());
        $trick->setUser($user);
        $trick->setSlug($url);
        $trick->setDescription($form->get('description')->getData());
        $trick->setCategory($form->get('category')->getData());
        $trick->setCreatedAt(new DateTime());

        foreach($form->get('videos')->getData() as $video) {
            $video->setAddedAt(new DateTime());
            $trick->addVideo($video);
        }

        foreach($form->get('images') as $imageForm) {
            $image = $imageForm->getData();
            $fileInfo = $this->uploader->upload($imageForm->get('file')->getData());
            $image->setExtension($fileInfo['extension']);
            $image->setFilename($fileInfo['filename']);
            $image->setCreatedAt(new DateTime());
            $trick->addImage($image);
        }
    }

    public function edit(FormInterface $form, Trick $trick)
    {

        $this->videoHydrater->edit($form->get('videos'), $trick->getVideos());
        $this->imageHydrater->edit($form->get('images'), $trick->getImages());

        $trick->setSlug($this->slugger->slug($trick->getName()));
    }
}