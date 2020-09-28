<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminPostsController extends AbstractController
{
    /**
     * @Route("/admin/posts", name="admin_posts_index")
     */
    public function index(ArticleRepository $repo)
    {
        return $this->render('admin/posts/index.html.twig', [
            'posts' => $repo->getArticles('article')
        ]);
    }

    /**
     * cette méthode traite l'affichage des articles
     * @Route("/admin/posts/ajout", name="admin_posts_ajout")
     * @return void
     */
    public function ajoutArticle(ObjectManager $manager, Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->getTypeArticle()->setLibelle('article');
            $manager->persist($article);
            $manager->flush();
            $this->addFlash("success", "Enregistrement effectué avec succès !");
            return $this->redirectToRoute('admin_posts_index');
        }
        return $this->render('admin/posts/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite la modification d'un article
     * @Route("/admin/posts/edit/{id}", name="admin_posts_edit")
     * @return response
     */
    public function editArticle(Article $article, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->getTypeArticle()->setLibelle('article');
            $manager->persist($article);
            $manager->flush();
            $this->addFlash("success", "Modification effectuée avec succès !");
            return $this->redirectToRoute('admin_posts_index');
        }
        return $this->render("admin/posts/ajout.html.twig", [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * cette méthode traite la suppression d'un article
     * @Route("/admin/posts/delete/{id}", name="admin_posts_delete")
     * @return void
     */
    public function deletePosts(Article $article, ObjectManager $manager)
    {
        $manager->remove($article);
        $manager->flush();
        $this->addFlash("success", "Suppression effectuée avec succès !");
        return $this->redirectToRoute('admin_posts_index');
    }
}
