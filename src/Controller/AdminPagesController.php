<?php

namespace App\Controller;

use App\Form\MenuType;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPagesController extends AbstractController
{
    /**
     * @Route("/admin/pages", name="admin_pages_index")
     */
    public function index(ArticleRepository $repo)
    {
        return $this->render('admin/pages/index.html.twig', [
            'pages' => $repo->getArticles('page')
        ]);
    }

    /**
     * cette méthode traite l'ajout d'une apage
     * @Route("/admin/pages/ajout", name="admin_pages_ajout")
     * 
     */
    public function ajoutPage(Request $request, ObjectManager $manager)
    {
        $a = new Article();
        $form = $this->createForm(ArticleType::class, $a);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $a->setPublication(true);
            $manager->persist($a);
            $manager->flush();
            $this->addFlash("success", 'Enregisitrement effectué avec succès !');
            return $this->redirectToRoute('admin_pages_index');
        }
        return $this->render(
            'admin/pages/ajout.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * cette méthode traite la modification d'une page
     * @Route("/admin/pages/edit/{id}", name="admin_pages_edit")
     *
     * @return void
     */
    public function editPage(ObjectManager $manager, Article $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDateModification(new \DateTime());
            $manager->persist($article);
            $manager->flush();
            $this->addFlash("success", "Modification de la page <strong>{$article->getMenu()}</strong> effectuée avec succès !");
            return $this->redirectToRoute('admin_pages_index');
        }
        return $this->render("admin/pages/ajout.html.twig", [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite la suppression d'une page
     * @Route("/admin/pages/delete/{id}", name="admin_pages_delete")
     * @return void
     */
    public function deletePage(ObjectManager $manager, Article $article)
    {
        $manager->remove($article);
        $manager->flush();
        $this->addFlash("warning", "La suppression de la page <strong>{$article->getTitre()}</strong> est effectuée avec succès");
        return $this->redirectToRoute('admin_pages_index');
    }
    /**
     * cette méthode traite la gestion des menus
     * @Route("/admin/pages/menu", name="admin_pages_menu")
     */
    public function Menu(ArticleRepository $repo)
    {
        return $this->render("admin/pages/menu.html.twig", [
            'menus' => $repo->getMenu(),
            'sousmenus' => $repo->getSousMenu()
        ]);
    }

    /**
     * cette méthode traite la modification et réorganisation des menus
     * @Route("/admin/pages/menu_edit/{id}",name="admin_pages_menu_edit")
     *
     * @return void
     */
    public function edit(ObjectManager $manager, Request $request, Article $article)
    {
        $form = $this->createForm(MenuType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();
            $this->addFlash("success", "Enregistrement effectué avec succès !");
            return $this->redirectToRoute("admin_pages_menu");
        }
        return $this->render("admin/pages/edit_menu.html.twig", [
            'form' => $form->createView(),
            'menu' => $article
        ]);
    }
}
