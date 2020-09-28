<?php

namespace App\Controller;

use App\Entity\Filiale;
use App\Form\FilialeType;
use App\Repository\FilialeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminFilialeController extends AbstractController
{
    /**
     * @Route("/admin/filiale", name="admin_filiale_index")
     */
    public function index(FilialeRepository $filiale)
    {
        return $this->render('admin/filiale/index.html.twig', [
            'filiales' => $filiale->findAll()
        ]);
    }

    /**
     * cette méthode traite l'ajout des filiales
     * @route("/admin/filiale/ajout", name="admin_filiale_ajout")
     * @return response
     */
    public function ajout(ObjectManager $manager, Request $request)
    {
        $filiale = new Filiale();
        $form = $this->createForm(FilialeType::class, $filiale);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($filiale);
            $manager->flush();
            $this->addFlash("success", "Enregistrement effectué avec succès !");
            return $this->redirectToRoute("admin_filiale_index");
        }
        return $this->render('admin/filiale/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Cette méthode traite la modification des données
     * @Route("/admin/filiale/edit/{id}", name="admin_filiale_edit")
     *
     * @return response
     */
    public function edit(ObjectManager $manager, Request $request, Filiale $filiale)
    {
        $form = $this->createForm(FilialeType::class, $filiale);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($filiale);
            $manager->flush();
            $this->addFlash("success", "Enregistrement effectué avec succès !");
            return $this->redirectToRoute('admin_filiale_index');
        }
        return $this->render('admin/filiale/ajout.html.twig', [
            'filiale' => $filiale,
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite la suppression des filiales
     * @Route("/admin/filiale/delete/{id}", name="admin_filiale_delete")
     * @return response
     */
    public function delete(ObjectManager $manager, Filiale $filiale)
    {
        $manager->remove($filiale);
        $manager->flush();
        $this->addFlash("success", "Suppression effectuée avec succès !");
        return $this->redirectToRoute('admin_filiale_index');
    }
}
