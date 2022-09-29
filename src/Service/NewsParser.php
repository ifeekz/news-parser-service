<?php

namespace App\Service;

use Goutte\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class NewsParser
{
    private $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @param data
     */
    public function scrape($data): array
    {
        // TO DO: Accept site argument and use site parameter from config service
        $url = $data['url'];
        $titlesXPath = $data['titlesXPath'];
        $descriptionsXPath = $data['descriptionsXPath'];
        $picturesXPath = $data['picturesXPath'];
        
        $httpClient = new Client();
        $response = $httpClient->request('GET', $url);
        $titles = $response->evaluate($titlesXPath);
        $descriptions = $response->evaluate($descriptionsXPath);
        $pictures = $response->evaluate($picturesXPath)->extract(['src']);
        
        $newsArray = [];
        $descriptionsArray = [];
        $picturesArray = [];
        foreach ($descriptions as $key => $description) {
            $descriptionsArray[] = $description->textContent;
        }

        foreach ($pictures as $key => $picture) {
            $picturesArray[] = $picture;
        }
        
        foreach ($titles as $key => $title) {
            $newsArray[] = [
                'title' => $title->textContent, 
                'description' => $descriptionsArray[$key],
                'picture' => $picturesArray[$key]
            ];
        }

        return $newsArray;
    }
}