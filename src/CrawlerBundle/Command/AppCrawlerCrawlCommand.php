<?php

namespace CrawlerBundle\Command;

use PicoFeed\PicoFeedException;
use PicoFeed\Reader\Reader;
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

        try {
            $reader = new Reader;
            $resource = $reader->download($siteConfig['feed_url']);
            $parser = $reader->getParser(
                $resource->getUrl(),
                $resource->getContent(),
                $resource->getEncoding()
            );

            $feed = $parser->execute();
            $this->getContainer()->get('ElasticsearchFeedRepository')->bulkUpsert($feed);
        }
        catch (PicoFeedException $e) {
        }
    }
}
