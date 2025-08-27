<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\ApplicabilityChecker;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;

class QueryApplicabilityChecker implements QueryApplicabilityCheckerInterface
{
    /**
     * @var string
     */
    protected const APP_CONFIG_SETTING_SOURCE_IDENTIFIERS = 'source_identifiers';

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
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isQueryApplicable(SearchContextTransfer $searchContextTransfer): bool
    {
        if (!$this->storeClient->isCurrentStoreDefined()) {
            return false;
        }

        $searchHttpConfigTransfer = $this->configReader->findSearchConfig(new SearchHttpConfigCriteriaTransfer());
        if (!$searchHttpConfigTransfer) {
            return false;
        }

        if (!isset($searchHttpConfigTransfer->getSettings()[static::APP_CONFIG_SETTING_SOURCE_IDENTIFIERS])) {
            return true;
        }

        return $searchContextTransfer->getSourceIdentifier() === '*'
            || in_array(
                $searchContextTransfer->getSourceIdentifier(),
                $searchHttpConfigTransfer->getSettings()[static::APP_CONFIG_SETTING_SOURCE_IDENTIFIERS],
                true,
            );
    }
}
