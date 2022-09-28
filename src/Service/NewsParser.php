<?php

namespace App\Service;

use Goutte\Client;

class NewsParser
{
    /**
     * @param data
     */
    public static function scrape($data): array
    {
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
            $descriptionsArray[] = ['description' => $description->textContent];
        }

        foreach ($pictures as $key => $picture) {
            $picturesArray[] = ['picture' => $picture];
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