<?php
/**
 * Created by PhpStorm.
 * User: abarry
 * Date: 23/06/2017
 * Time: 18:27
 */

namespace CrawlerBundle\Elasticsearch;

use Elasticsearch\ClientBuilder;


/**
 * Class ElasticSearchClient
 * @package AppBundle
 */
class ElasticsearchClientBuilder
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $client;

    /**
     * ElasticSearchClient constructor.
     *
     * @param string          $host
     * @param int             $port
     * @param string          $scheme
     * @param string          $userBasic
     * @param string          $passwordBasic
     */
    public function __construct(string $host, int $port, string $scheme, string $userBasic, string $passwordBasic)
    {
        $this->client = ClientBuilder::create()
            ->setHosts([
                [
                    'host'   => $host,
                    'port'   => (string) $port,
                    'scheme' => $scheme,
                    'user'   => $userBasic,
                    'pass'   => $passwordBasic
                ]
            ])
            ->build();
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}