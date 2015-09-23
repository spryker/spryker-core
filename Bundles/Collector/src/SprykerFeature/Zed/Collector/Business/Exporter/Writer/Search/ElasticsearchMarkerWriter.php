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

    const WRITER_NAME = 'elasticsearch-marker-writer';

    /**
     * @var Index
     */
    protected $index;

    /**
     * @param Client $searchClient
     * @param string $indexName
     */
    public function __construct(Client $searchClient, $indexName)
    {
        $this->index = $searchClient->getIndex($indexName);
    }

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        $mapping = new Mapping($this->index->getType($type));

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
        return self::WRITER_NAME;
    }

}
