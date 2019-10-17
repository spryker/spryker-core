<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Index;

use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    /**
     * @var \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreInterface
     */
    protected $store;

    /**
     * @param \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreInterface $storeClient
     */
    public function __construct(SearchElasticsearchToStoreInterface $storeClient)
    {
        $this->store = $storeClient;
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    public function resolve(string $sourceIdentifier): string
    {
        $indexName = sprintf(
            '%s_%s',
            $this->store->getStoreName(),
            $sourceIdentifier
        );

        return mb_strtolower($indexName);
    }
}
