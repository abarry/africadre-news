<?php

namespace CrawlerBundle\Elasticsearch;

use PicoFeed\Parser\Feed;

/**
 * Class ElasticsearchFeedRepository
 * @package AppBundle
 */
class ElasticsearchFeedRepository extends ElasticsearchBaseRepository
{
    public function bulkUpsert(Feed $feed)
    {
        foreach ($feed->getItems() as $item) {

            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_type'  => $this->type,
                    '_id'    => $item->getId()
                ]
            ];
            
            $params['body'][] = FeedDocumentBuilder::build($feed, $item);
        }
        try {
            if (!empty($params['body'])) {
                $this->client->bulk($params);
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
