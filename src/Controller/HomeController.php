<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * This method displays the home page, the first set of a list of tricks.
     *
     * @Route("/", name="shadow_home")
     * @Route("/accueil", name="home")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function home(TrickRepository $trickRepository):Response
    {
        $maxPerPages = $this->getParameter("maxPerPage");
        $tricks = $trickRepository->findAndPaginate(1, $maxPerPages);
        $totalTricks = count($trickRepository->findAll());

        return $this->render('home/home.html.twig', [
            'tricks' => $tricks,
            'page' => 1,
            'max_pages' => ceil( $totalTricks / $maxPerPages),
            'landing_img' => $this->getParameter('landing_img'),
        ]);
    }

    /**
     * This method gets the requested paginated set of tricks and returns it to the client as a JsonResponse.
     *
     * @Route("/home/ajax/{page}", name="ajax_load", requirements={"page"="\d+"})
     * @param int $page
     * @param Request $request
     * @param TrickRepository $trickRepository
     * @return JsonResponse
     */
    public function ajaxLoad(int $page, Request $request, TrickRepository $trickRepository):JsonResponse
    {
/*        if(!$request->isXmlHttpRequest())
        {
            $routeName = $request->attributes->get('_route');
            throw new BadRequestHttpException("The route '".$routeName."' must be an XML or JSON HTTP request.");
        }*/

        $tricks = $trickRepository->findAndPaginate($page, $this->getParameter("maxPerPage"));

        $tricksTemplate = $this->renderView('home/blocks/thumbnail.html.twig', [
            'tricks' => $tricks
        ]);

        return new JsonResponse(['template' => $tricksTemplate]);
    }
}
