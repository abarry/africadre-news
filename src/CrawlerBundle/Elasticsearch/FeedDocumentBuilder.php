<?php
/**
 * Created by PhpStorm.
 * User: abarry
 * Date: 23/06/2017
 * Time: 18:27
 */

namespace CrawlerBundle\Elasticsearch;



use PicoFeed\Parser\Feed;
use PicoFeed\Parser\Item;

class FeedDocumentBuilder
{
    public static function build(Feed $feed, Item $item) : array
    {
        return [
            'id'    => $feed->getId(),
            'title' => $feed->getTitle(),
            'description' => $feed->getDescription(),
            'language'  => $feed->getLanguage(),
            'site'  => $feed->getSiteUrl(),
            'items' => [
                'id'            => $item->getId(),
                'title'         => $item->getTitle(),
                'url'           => $item->getUrl(),
                'author'        => $item->getAuthor(),
                'createdAt'     => new \DateTime(),
                'publishedDate' => $item->getPublishedDate(),
                'updatedDate'   => $item->getUpdatedDate(),
                'content'       => $item->getContent(),
                'language'      => $item->getLanguage(),
                'categories'    => $item->getCategories()
            ]
        ];
    }
}