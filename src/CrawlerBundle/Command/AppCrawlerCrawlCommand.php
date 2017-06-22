<?php

namespace CrawlerBundle\Command;

use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCrawlerCrawlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:crawler:crawl')
            ->setDescription('Fetch a given feed and store it in the data stores')
            ->addArgument('site', InputArgument::REQUIRED, 'The site to crawl')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site = $input->getArgument('site');
        $sites = $this->getContainer()->getParameter('sites');

        if (!$sites[$site]) {
            throw new \InvalidArgumentException(sprintf("%s site doesn't exist", $site));
        }
        $siteConfig = $sites[$site];
        $client = new Client();
        $response = $client->get($siteConfig['feed_url']);
        $xml = new \SimpleXMLElement($response->getBody()->getContents());

        $esHost = $this->getContainer()->getParameter('es_host');
        $esPort = $this->getContainer()->getParameter('es_port');
        /**
         * @var \Elasticsearch\Client
         */
        $esClient = ClientBuilder::create()
            ->setHosts([
                'host' => $esHost,
                'port' => $esPort,
            ])
            ->build();

        $esClient->indices()->create(['index'=>'africadre-news']);

        $article = $xml->channel->item;
        $esClient->create(
            [
                'index' => 'africadre-news',
                'type'  => 'article',
                'id'    => md5($article->link),
                'body'  => [
                    'title' => $article->title,
                    'link'  => $article->link,
                    'comments'  => $article->comments,
                    'category'  => $article->category,
                    'description'   => $article->description
                ]
            ]
        );
    }

}
