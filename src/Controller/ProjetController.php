<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetcompteType;
use App\Repository\FichiersProjetRepository;
use App\Repository\ProjetRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ProjetController extends AbstractController
{
    /**
     * cette méthode liste les projets d'un user
     * @Route("/projet/vue", name="account_projet_index")
     */
    public function index(ProjetRepository $repo)
    {
        $user = $this->getUser();
        return $this->render('projet/index.html.twig', [
            'asidemenu' => 'projet',
            'projets' => $repo->findByUser($user)
        ]);
    }

    /**
     * Cette méthode traite l'ajout(creation) d'un projet pour un abonné
     * @Route("/projet/create", name="account_projet_create")
     */
    public function createprojet(ObjectManager $em, Request $req)
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetcompteType::class, $projet);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setUser($this->getUser());
            $em->persist($projet);
            $em->flush();
            $this->addFlash('success', 'l\'ajoute de votre projet a bien été effectué');
            return $this->redirectToRoute('account_projet_index');
        }
        return $this->render("projet/create.html.twig", [
            'form' => $form->createView(),
            'asidemenu' => 'projetcreate'
        ]);
    }

    /**
     * cette méthode traite la modification des données
     * @Route("/projet/update/{id}", name="projet_account_update")
     */
    public function updateprojet(ObjectManager $em, Request $req, Projet $projet)
    {
        $form = $this->createForm(ProjetcompteType::class, $projet);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            $this->addFlash('success', 'Modification effectuée avec succès !');
            return $this->redirectToRoute('account_projet_index');
        }
        return $this->render("projet/create.html.twig", [
            'form' => $form->createView(),
            '$projet' => $projet,
            'asidemenu' => 'projetcreate'
        ]);
    }

    /**
     * cette méthode traite la suppression d'un projet
     * @Route("/projet/delete/{id}", name="projet_account_delete")
     */
    public function deleteprojet(ObjectManager $em, Projet $projet, FichiersProjetRepository $repo)
    {
        if (!empty($projet)) {
            $em->remove($projet);
            $em->flush();
            $this->addFlash('success', 'Suppression effectué avec succès !');
        } else {
            $this->addFlash('warning', 'Ce projet n\'existe pas dans la base de données');
        }
        return $this->redirectToRoute('account_projet_index');
    }


    /**
     * cette méthode traite l'affichage d'un projet pour visualiser
     * @Route("/projet/show/{id}", name="projet_account_show")
     */
    public function show(Projet $projet, Request $request)
    {
        //
        if (empty($projet)) {
            $this->addFlash('warning', 'Ce projet n\'existe pas');
            echo 'NO';
        }
        //On verifie si la requette est de l'ajax ou normale
        if ($request->isXmlHttpRequest()) {
            return $this->render("projet/show.html.twig", [
                "projet" => $projet,
                'asidemenu' => 'projet'
            ]);
        } else {
            return $this->render('projet/showpage.html.twig', [
                'projet' => $projet
            ]);
        }
    }
}
