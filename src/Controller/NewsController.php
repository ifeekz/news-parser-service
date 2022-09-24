<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $news = "";

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }
}
