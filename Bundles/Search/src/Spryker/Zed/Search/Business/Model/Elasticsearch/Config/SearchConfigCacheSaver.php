<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Search\SearchConstants;

class SearchConfigCacheSaver implements SearchConfigCacheSaverInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer[] $facetConfigTransfers
     * @param \Generated\Shared\Transfer\SortConfigTransfer[] $sortConfigTransfers
     *
     * @return void
     */
    public function save(array $facetConfigTransfers, array $sortConfigTransfers)
    {
        $searchConfigCacheTransfer = new SearchConfigCacheTransfer();

        foreach ($facetConfigTransfers as $facetConfigTransfer) {
            $searchConfigCacheTransfer->addFacetConfig($facetConfigTransfer);
        }

        foreach ($sortConfigTransfers as $sortConfigTransfer) {
            $searchConfigCacheTransfer->addSortConfig($sortConfigTransfer);
        }

        $this
            ->storageClient
            ->set(SearchConstants::SEARCH_CONFIG_CACHE_KEY, $searchConfigCacheTransfer->toArray());
    }

}
