<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Reader\Search;

use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Index;
use Elastica\Type\Mapping;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class ElasticsearchMarkerReader implements ReaderInterface
{

    const READER_NAME = 'elastic-search-marker-reader';
    const META_ATTRIBUTE = '_meta';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param Client $searchClient
     * @param string $indexName
     * @param string $type
     */
    public function __construct(Client $searchClient, $indexName, $type)
    {
        $this->client = $searchClient;
        $this->index = $this->client->getIndex($indexName);
        $this->type = $type;
    }

    /**
     * @param string $key
     *
     * @return string|bool
     */
    public function read($key)
    {
        try {
            $mapping = $this->index->getType($this->type)->getMapping();
        } catch (ResponseException $e) {
            return false;
        }

        if (isset($mapping[$this->type][self::META_ATTRIBUTE][$key])) {
            return $mapping[$this->type][self::META_ATTRIBUTE][$key];
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::READER_NAME;
    }

}
