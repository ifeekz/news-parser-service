<?php
namespace App\Command;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Service\NewsParser;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseNewsCommand extends Command
{
    private $newsParser;
    private $newsRepository;
    
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:scrape-news';
    protected static $defaultDescription = 'Scrapes news from url';

    public function __construct(NewsParser $newsParser, NewsRepository $newsRepository)
    {
        $this->newsParser = $newsParser;
        $this->newsRepository = $newsRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to scrape news from a news site...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TO DO: Accept site input argument to indicate the site to scrape 
        $data = [
            'url' => 'https://highload.today/',
            'titlesXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//a//h2',
            'descriptionsXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//p',
            'picturesXPath' => '//div[@class="col sidebar-center"]//div[@class="lenta-item"]//div[@class="lenta-image"]//img'
        ];

        try{
            $scrapedNews = $this->newsParser->scrape($data);
            foreach ($scrapedNews as $data) {
                $news = new News();
                $news->setTitle($data['title']);
                $news->setShortDescription($data['description']);
                $news->setPicture($data['picture']);
                $news->setCreatedAt(new DateTimeImmutable());
                $this->newsRepository->add($news, true);
            }
            $output->writeln('Scraping successfully completed!');
            return Command::SUCCESS;
        } catch(\Exception $e) {
            return Command::FAILURE;
        }
    }
}