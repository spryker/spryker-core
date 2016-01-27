<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Index;
use Elastica\Type\Mapping;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class _ElasticsearchMarkerWriter implements WriterInterface
{

    const WRITER_NAME = 'elasticsearch-marker-writer';

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
     * TODO stateful property must be refactored
     *
     * @var array
     */
    protected $metaData = [];

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
        foreach ($dataSet as $key => $value) {
            $this->metaData[$key] = $value;
        }
    }

    /**
     * TODO Needs refactoring
     */
    public function __destruct()
    {
        if (empty($this->metaData) === false) {
            $mapping = new Mapping($this->index->getType($this->type));
            $mapping->setMeta($this->metaData)->send();
        }
    }

    /**
     * Deletes all timestamps. Parameter $dataSet is ignored.
     * TODO Needs refactoring
     *
     * @param array $dataSet
     *
     * @throws \Exception
     *
     * @return void
     */
    public function delete(array $dataSet)
    {
        $mapping = new Mapping($this->index->getType($this->type));
        $mapping->setMeta(['' => ''])->send(); // Empty mapping causes ClassCastException[java.util.ArrayList cannot be cast to java.util.Map]
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::WRITER_NAME;
    }

}
