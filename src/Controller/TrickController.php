<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Form\MessageType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\Slugger;
use App\Services\Uploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

/**
 * CRUD controller for trick related operations.
 *
 * @Route("/figure")
 */
class TrickController extends AbstractController
{
    /**
     * Controls the creation of a new trick.
     *
     * @Route("/ajouter", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Slugger $slugger
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Slugger $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $form->getData();
            $trick->setUser($this->getUser());
            $trick->setSlug($slugger->slug($trick->getName()));
            $trick->setCreatedAt(new DateTime());
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', [ 'id' => $trick->getId() ]);
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Controls the display of a single trick.
     *
     * @Route("/afficher/{slug}", name="trick_show", methods={"GET", "POST"})
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $comment = new Message();
        $form = $this->createForm(MessageType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new DateTime());
            $comment->setTrick($trick);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', "Votre commentaire a été ajouté à la liste de discussion de cette figure.");
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comment_form' => $form->createView(),
        ]);
    }

    /**
     * Controls the edition of a trick.
     *
     * @Route("/editer/{slug}", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @param Uploader $uploader
     * @param Slugger $slugger
     * @return Response
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, Uploader $uploader, Slugger $slugger): Response
    {
        $imagesWitness = new ArrayCollection();
        $videosWitness = new ArrayCollection();
        foreach($trick->getImages() as $image)
        {
            $imagesWitness->add($image);
        }

        foreach($trick->getVideos() as $video)
        {
            $videosWitness->add($video);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images');
            dump($images);
            $videos = $form->get('videos');

            // Assurer l'intégrité de la base de données.
            foreach($images as $image)
            {
                $imageEntity = $image->getData();
                foreach($imagesWitness as $originalImage)
                {
                    // Si l'image a été retirée de l'entité, trouver l'entité image et la supprimer.
                    if(!$trick->getImages()->contains($originalImage))
                    {
                        $entityManager->remove($originalImage);
                        $path = $this->getParameter("images_directory").'/'
                            .$originalImage->getFilename().'.'.$originalImage->getExtension();
                        $uploader->remove($path);
                    }
                }

                $imageFile = $image->get('file')->getData();
                if($imageFile)
                {
                    $fileInfo = $uploader->upload($imageFile);
                    
                    $imageEntity->setExtension($fileInfo['extension']);
                    $imageEntity->setFilename($fileInfo['filename']);
                    $imageEntity->setCreatedAt(new DateTime());

                    $entityManager->persist($imageEntity);
                }
            }

            foreach($videos as $video)
            {
                $videoEntity = $video->getData();
                foreach($videosWitness as $originalVideo)
                {
                    if(!$trick->getVideos()->contains($originalVideo))
                    {
                        $entityManager->remove($originalVideo);
                    }
                }
                $videoEntity->setTrick($trick);
                $entityManager->persist($videoEntity);
            }
            $trick->setSlug($slugger->slug($trick->getName()));
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', "La modification de votre figure a bien été prise en compte.");
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Controls the deletion of a trick.
     *
     * @Route("/supprimer/{slug}", name="trick_delete", methods={"GET", "DELETE"})
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($trick);

        $entityManager->flush();
        $this->addFlash('success', "La figure '" . $trick->getName() . "' a bien été supprimée.");

        return $this->redirectToRoute('home');
    }
}
