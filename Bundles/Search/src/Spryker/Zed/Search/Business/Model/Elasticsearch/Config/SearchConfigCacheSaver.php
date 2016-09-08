<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Library\Json;
use Spryker\Shared\Search\SearchConstants;

/**
 * @deprecated Provide a list of \Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface
 * in \Pyz\Client\Search\SearchDependencyProvider::createSearchConfigExpanderPlugins() instead.
 */
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
     * @param \Generated\Shared\Transfer\SearchConfigCacheTransfer $searchConfigCacheTransfer
     *
     * @return void
     */
    public function save(SearchConfigCacheTransfer $searchConfigCacheTransfer)
    {
        $searchConfigCacheKey = $this->getSearchConfigCacheKey();
        $this
            ->storageClient
            ->set($searchConfigCacheKey, Json::encode($searchConfigCacheTransfer->toArray()));
    }

    /**
     * @return string
     */
    protected function getSearchConfigCacheKey()
    {
        return Config::get(SearchConstants::SEARCH_CONFIG_CACHE_KEY);
    }

}
