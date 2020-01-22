<?php

namespace App\Controller;

use App\Entity\Presse;
use App\Entity\Article;
use App\Entity\Theme;
use App\Entity\Animation;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GabareLifeController extends AbstractController
{
    /**
     * @Route("/la-vie-de-la-gabare", name="gabare_life")
     */
    public function index()
    {
        $pressArticle = $this->getDoctrine()
            ->getRepository(Presse::class)
            ->findAll();


        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(
                [],
                ['date' => 'DESC'],
                3
            );

        $themes = $this->getDoctrine()
            ->getRepository(Theme::class)
            ->findAll();

        $animations = $this->getDoctrine()
            ->getRepository(Animation::class)
            ->findBy(
                [],
                ['schedule' => 'ASC'],
                6
            );

        return $this->render('gabare_life/index.html.twig', [
            'pressArticles' => $pressArticle,
            'articles' => $articles,
            'themes' => $themes,
            'animations' => $animations,
        ]);
    }
}
