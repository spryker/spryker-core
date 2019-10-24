<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Index;

use Elastica\Client;
use Elastica\Index as ElasticaIndex;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class Index implements IndexInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Elastica\Client $client
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(Client $client, SearchElasticsearchConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function openIndex(?SearchContextTransfer $searchContextTransfer = null): bool
    {
        return $this->getIndex($searchContextTransfer)->open()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(?SearchContextTransfer $searchContextTransfer = null): bool
    {
        return $this->getIndex($searchContextTransfer)->close()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(?SearchContextTransfer $searchContextTransfer = null): bool
    {
        return $this->getIndex($searchContextTransfer)->delete()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return \Elastica\Index
     */
    protected function getIndex(?SearchContextTransfer $searchContextTransfer): ElasticaIndex
    {
        $indexName = $this->resolveIndexNameFromSearchContextTransfer($searchContextTransfer);

        return $this->client->getIndex($indexName);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return string
     */
    protected function resolveIndexNameFromSearchContextTransfer(?SearchContextTransfer $searchContextTransfer): string
    {
        if (!$searchContextTransfer) {
            return $this->config->getIndexNameAll();
        }

        $this->assertIndexNameIsSet($searchContextTransfer);

        return $searchContextTransfer->getElasticsearchContext()->getIndexName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    protected function assertIndexNameIsSet(SearchContextTransfer $searchContextTransfer): void
    {
        $searchContextTransfer->requireElasticsearchContext()->getElasticsearchContext()->requireIndexName();
    }
}
