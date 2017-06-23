<?php

namespace CrawlerBundle\Command;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppCrawlerCreateIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:crawler:create-index')
            ->setDescription('Create an elasticsearch index')
            ->addArgument('name', InputArgument::REQUIRED, 'The index name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        try {
            $this->getContainer()->get('ElasticsearchClientBuilder')->getClient()->indices()->create([
                'index' => $name
            ]);
        }
        catch (BadRequest400Exception $e) {
        }
    }
}
