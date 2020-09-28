<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProjetController extends AbstractController
{
    /**
     * @Route("/admin/projet", name="admin_projet_index")
     */
    public function index(ProjetRepository $repo)
    {
        return $this->render('admin/projet/index.html.twig', [
            'projets' => $repo->findAll()
        ]);
    }
    /**
     * cette méthode traite l'ajout d'un projet
     * @Route("admin/projet/ajout", name="admin_projet_ajout")
     *
     * @return Response
     */
    public function ajout(Request $request, ObjectManager $manager)
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($projet);
            $manager->flush();
            $this->addFlash('success', 'Enregistrement effectué avec succès !');
            return $this->redirectToRoute('admin_projet_index');
        }
        return $this->render('admin/projet/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite la modification d'un projet
     * @Route("/admin/projet/edit/{id}", name="admin_projet_edit")
     * @return Response
     */
    public function edit(ObjectManager $manager, Request $request, Projet $projet)
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($projet);
            $manager->flush();
            $this->addFlash("success", "Enregistrement effectué avec succès !");
            return $this->redirectToRoute('admin_projet_index');
        }
        return $this->render("admin/projet/ajout.html.twig", [
            'projet' => $projet,
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode supprime un porjet
     * @Route("/admin/projet/delete/{id}", name="admin_projet_delete")
     *
     * @return Response
     */
    public function delete(ObjectManager $manager, Projet $projet)
    {
        $manager->remove($projet);
        $manager->flush();
        $this->addFlash("success", "Enregistrement effectué avec succès !");
        return $this->redirectToRoute('admin_projet_index');
    }
}
