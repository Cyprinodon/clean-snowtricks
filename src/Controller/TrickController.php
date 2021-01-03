<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Form\MessageType;
use App\Form\TrickType;
use App\Services\TrickHydrater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param TrickHydrater $trickHydrater
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, TrickHydrater $trickHydrater): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickHydrater->create($form, $trick, $this->getUser());
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', [ 'slug' => $trick->getSlug() ]);
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
     * @param TrickHydrater $trickHydrater
     * @return Response
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, TrickHydrater $trickHydrater): Response
    {

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickHydrater->edit($form, $trick);

            $entityManager->persist($trick);
            $entityManager->flush();
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
