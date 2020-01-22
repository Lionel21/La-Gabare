<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleTypeSearchType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/article", name="article_")
 */
class ArticleController extends AbstractController
{
    const ARTICLES = 9;
    /**
     * @Route("/", name="list")
     * @return Response
     */
    public function list(
        ArticleRepository $articleRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(ArticleTypeSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $theme = $data["theme"];
            $articles = $articleRepository->findArticleByTheme($theme);
        } else {
            $articles = $articleRepository->findBy(
                [],
                ['date' => 'DESC']
            );
        }
      
        $articles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            self::ARTICLES
        );

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/afficher/{slug}", name="show")
     * @param Article $article
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
