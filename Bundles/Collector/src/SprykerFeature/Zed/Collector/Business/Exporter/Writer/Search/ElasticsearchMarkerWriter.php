<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Index;
use Elastica\Type\Mapping;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ElasticsearchMarkerWriter implements WriterInterface
{

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
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        $mapping = new Mapping($this->index->getType($this->type));

        foreach ($dataSet as $key => $value) {
            $mapping->setMeta([$key => $value])->send();
        }
    }

    /**
     * @param array $dataSet
     *
     * @throws \Exception
     */
    public function delete(array $dataSet)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elasticsearch-marker-writer';
    }

}
