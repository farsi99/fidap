<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CommentType;
use App\Entity\Commentaire;
use App\Entity\Inviter;
use App\Repository\ArticleRepository;
use App\Service\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Proxies\__CG__\App\Entity\Commentaire as ProxiesCommentaire;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
     * cette méthode traite l'affichage des articles
     * @Route("/actualites/{page<\d+>?1}", name="articles_index")
     */
    public function index(ArticleRepository $articles, $page, Pagination $pagination)
    {   //pagination
        $pagination->setEntityClass(Article::class)
            ->setPage($page);

        return $this->render('article/index.html.twig', [
            'articles' => $pagination->getData(['typeArticle' => '38']),
            'totalpages' => $pagination->getTotalPage(['typeArticle' => '38']),
            'page' => $page
        ]);
    }

    /**
     * cette méthode traite l'affichage d'un article
     * @Route("/article/{slug}", name="article_show")
     */
    public function show($slug, ArticleRepository $reqarticle, Request $request, ObjectManager $em)
    {
        $comment = new Commentaire();
        $user = new Inviter();
        $article = $reqarticle->findOneBy(['slug' => $slug]);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //on crée un user
            $user->setNom($request->get('nom'));
            $user->setPrenom($request->get('prenom'));
            $user->setEmail($request->get('email'));
            $em->persist($user);

            //on enregistre le commentaire
            $comment->setArticle($article);
            $comment->setDateCreation(new \DateTime());
            $comment->setInviter($user);

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'votre commentaire est bien ajouté');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentaires' => $em->getRepository(Commentaire::class)->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode traite l'ajout d'un commentaire
     * @Route("/comment/{slug}/new", methods={"POST"}, name="ajout_comment")
     */
    public function ajoutComment(Article $article, Request $req, ObjectManager $em, $slug)
    {
        $comment = new Commentaire();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire est bien envoyé!');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article->findOneBy(['slug' => $slug]),
            'form' => $form->createView()
        ]);
    }
}
