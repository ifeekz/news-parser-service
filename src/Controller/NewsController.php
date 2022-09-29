<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\NewsParser;
use DateTime;
use DateTimeImmutable;

class NewsController extends AbstractController
{
    private $ITEMS_PER_PAGE = 10;
    /**
     * @Route("/")
     */
    public function index(NewsRepository $newsRepository): Response
    {
        $news = $newsRepository->getNewsPaginator($this->ITEMS_PER_PAGE);
        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }
}
