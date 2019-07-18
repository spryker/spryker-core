<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

use Elastica\Client;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class ElasticsearchReader implements ReaderInterface, ConfigurableSearchReaderInterface
{
    public const READER_NAME = 'elastic-search-reader';

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfiguration;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     */
    public function __construct(Client $searchClient, $indexName, $type)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return mixed
     */
    public function read($key, $type = '')
    {
        return $this->getType()->getDocument($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::READER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $collectorConfigurationTransfer
     *
     * @return void
     */
    public function setSearchCollectorConfiguration(SearchCollectorConfigurationTransfer $collectorConfigurationTransfer)
    {
        $this->searchCollectorConfiguration->fromArray($collectorConfigurationTransfer->modifiedToArray());
    }

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    public function getSearchCollectorConfiguration()
    {
        return $this->searchCollectorConfiguration;
    }

    /**
     * @return \Elastica\Type
     */
    protected function getType()
    {
        return $this->client
            ->getIndex($this->searchCollectorConfiguration->getIndexName())
            ->getType($this->searchCollectorConfiguration->getTypeName());
    }
}
