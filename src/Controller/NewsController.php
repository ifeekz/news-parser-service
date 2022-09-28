<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\NewsParser;

class NewsController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $data = [
            'url' => 'https://highload.today/',
            'titlesXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//a//h2',
            'descriptionsXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//p',
            'picturesXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//div[@class="lenta-image"]//img'
        ];
                
        $news = NewsParser::scrape($data);

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }
}
