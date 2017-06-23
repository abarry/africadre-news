<?php

namespace CrawlerBundle\Elasticsearch;


use Psr\Log\LoggerInterface;

/**
 * Class ElasticsearchBaseRepository
 * @package AppBundle
 */
class ElasticsearchBaseRepository
{
    /**
     * @var ElasticsearchClientBuilder $index
     */
    protected $client;

    /**
     * @var string $index
     */
    protected $index;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var LoggerInterface $type
     */
    protected $logger;

    public function __construct(ElasticsearchClientBuilder $clientBuilder, string $index, string $type, LoggerInterface $logger)
    {
        $this->client = $clientBuilder->getClient();
        $this->index = $index;
        $this->type = $type;
        $this->logger = $logger;
    }
}