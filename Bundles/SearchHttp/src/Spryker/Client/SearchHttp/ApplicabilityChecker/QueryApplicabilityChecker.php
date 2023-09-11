<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\ApplicabilityChecker;

use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;

class QueryApplicabilityChecker implements QueryApplicabilityCheckerInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface
     */
    protected ConfigReaderInterface $configReader;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface $configReader
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     */
    public function __construct(
        ConfigReaderInterface $configReader,
        SearchHttpToStoreClientInterface $storeClient
    ) {
        $this->configReader = $configReader;
        $this->storeClient = $storeClient;
    }

    /**
     * @return bool
     */
    public function isQueryApplicable(): bool
    {
        return $this->storeClient->isCurrentStoreDefined()
            && $this->configReader->getSearchHttpConfigCollectionForCurrentStore()
                ->getSearchHttpConfigs()->count() > 0;
    }
}
