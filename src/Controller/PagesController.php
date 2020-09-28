<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * cette méthode traite l'affichage d'une page
     * @Route("/{slugMenu}", name="pages_view")
     *
     * @return Response
     */
    public function getpage($slugMenu, ArticleRepository $repo)
    {
        return $this->render('pages/index.html.twig', [
            'page' => $repo->findByOnPage($slugMenu)[0]
        ]);
    }
}
