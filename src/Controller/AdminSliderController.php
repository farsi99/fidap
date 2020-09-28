<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminSliderController extends AbstractController
{
    /**
     * @Route("/admin/slider", name="admin_slider_index")
     */
    public function index(SliderRepository $repo)
    {
        return $this->render('admin/slider/index.html.twig', [
            'sliders' => $repo->findAll()
        ]);
    }

    /**
     * cette méthode traite l'ajout d'un slider
     * @Route("/admin/slider/ajout", name="admin_slider_ajout")
     * 
     */
    public function ajoutSlider(ObjectManager $manager, Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($slider);
            $manager->flush();
            $this->addFlash('success', "Enregistrement effectué avec succès !");
            return $this->redirectToRoute('admin_slider_index');
        }
        return $this->render('admin/slider/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite les modifications d'un slider
     * @Route("/admin/slider/edit/{id}", name="admin_slider_edit")
     */
    public function editer(ObjectManager $manager, Request $request, Slider $slider)
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($slider);
            $manager->flush();
            $this->addFlash('success', 'La modification est effecutée avec succès !');
            return $this->redirectToRoute('admin_slider_index');
        }
        return $this->render('admin/slider/ajout.html.twig', [
            'form' => $form->createView(),
            'slider' => $slider
        ]);
    }

    /**
     * suppression d'un slider
     * @Route("/admin/slider/delete/{id}", name="admin_slider_delete")
     */
    public function delete(ObjectManager $manager, Slider $slider)
    {
        $manager->remove($slider);
        $manager->flush();
        $this->addFlash('success', 'Suppression effectuée avec succès!');
        return $this->redirectToRoute('admin_slider_index');
    }
}
