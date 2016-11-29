<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Type\Mapping;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ElasticsearchMarkerWriter implements WriterInterface
{

    const WRITER_NAME = 'elasticsearch-marker-writer';

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Elastica\Index
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
     * @param \Elastica\Client $searchClient
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
     *
     * @return bool
     */
    public function write(array $dataSet)
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
        if (!empty($this->metaData)) {
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
