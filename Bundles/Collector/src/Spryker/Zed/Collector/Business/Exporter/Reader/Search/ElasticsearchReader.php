<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

use Elastica\Client;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Index\IndexFactoryInterface;

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
     * @var \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface
     */
    protected $indexFactory;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     * @param \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface $indexFactory
     */
    public function __construct(Client $searchClient, $indexName, $type, IndexFactoryInterface $indexFactory)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);

        $this->indexFactory = $indexFactory;
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return mixed
     */
    public function read($key, $type = '')
    {
        return $this->getIndex()->getDocument($key);
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
     * @return \Elastica\Index|\Spryker\Zed\Collector\Business\Index\IndexAdapterInterface
     */
    protected function getIndex()
    {
        return $this->indexFactory->createIndex($this->client, $this->getSearchCollectorConfiguration());
    }
}
