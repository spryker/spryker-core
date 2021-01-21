<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Index\IndexNameResolver;

use Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $searchElasticsearchConfig;

    /**
     * @var string|null
     */
    protected static $storeName;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface $storeClient
     * @param \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig $searchElasticsearchConfig
     */
    public function __construct(SearchElasticsearchToStoreClientInterface $storeClient, SearchElasticsearchConfig $searchElasticsearchConfig)
    {
        $this->storeClient = $storeClient;
        $this->searchElasticsearchConfig = $searchElasticsearchConfig;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    public function resolve(string $sourceIdentifier): string
    {
        $indexParameters = [
            $this->searchElasticsearchConfig->getIndexPrefix(),
            $this->getStoreName(),
            $sourceIdentifier,
        ];

        return mb_strtolower(implode('_', array_filter($indexParameters)));
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        if (static::$storeName === null) {
            $storeTransfer = $this->storeClient->getCurrentStore();

            static::$storeName = $storeTransfer->requireName()->getName();
        }

        return static::$storeName;
    }
}
