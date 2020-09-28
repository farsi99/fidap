<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\FilialeRepository;
use App\Repository\ProjetRepository;
use App\Repository\SliderRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{


    /**
     * @Route("/", name="accueil_index")
     */
    public function index(ObjectManager $manager, ArticleRepository $article, FilialeRepository $filiales, SliderRepository $slides, ProjetRepository $projets)
    {
        return $this->render('accueil/index.html.twig', [
            'title' => 'Nous investissons pour votre réussite',
            'sliders' => $slides->findAll(),
            'filiales' => $filiales->findAll(),
            'projets' => $projets->findBy([], ['id' => 'DESC'], 4),
            'articles' => $article->findBy(['publication' => 1], ['id' => 'DESC'], 6)
        ]);
    }
    /**
     * cette méthode traite l'affichage du contact
     * @Route("contact", name="contact")
     * @return response
     */
    public function contact()
    {
        return $this->render("contact.html.twig");
    }


    /**
     * cette méthode traite l'affichage de la page de connexion ou inscription
     * @Route("/inscription", name="inscription")
     * @return Response
     */
    public function insciption()
    { }
}
