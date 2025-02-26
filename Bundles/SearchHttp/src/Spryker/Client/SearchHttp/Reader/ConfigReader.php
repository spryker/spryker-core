<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Reader;

use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;
use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToSynchronizationServiceInterface;
use Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var bool
     */
    protected bool $isSearchHttpConfigCached = false;

    /**
     * @var \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    protected SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransferCache;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface $storageClient
     * @param \Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface $searchHttpConfigMapper
     */
    public function __construct(
        protected SearchHttpToStorageClientInterface $storageClient,
        protected SearchHttpToSynchronizationServiceInterface $synchronizationService,
        protected ConfigMapperInterface $searchHttpConfigMapper
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    public function getSearchHttpConfigCollectionForCurrentStore(): SearchHttpConfigCollectionTransfer
    {
        $this->loadSearchConfigForCurrentStore();

        return $this->searchHttpConfigCollectionTransferCache;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteria
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigTransfer|null
     */
    public function findSearchConfig(SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteria): ?SearchHttpConfigTransfer
    {
        $searchHttpConfigs = $this->getSearchHttpConfigCollectionForCurrentStore()->getSearchHttpConfigs();

        return $searchHttpConfigs->count() > 0 ? $searchHttpConfigs->getIterator()->current() : null;
    }

    /**
     * @return void
     */
    protected function loadSearchConfigForCurrentStore(): void
    {
        if ($this->isSearchHttpConfigCached) {
            return;
        }

        $searchConfig = $this->getSearchConfig();

        if ($searchConfig) {
            $this->searchHttpConfigCollectionTransferCache = $this->searchHttpConfigMapper
                ->mapSearchConfigToSearchHttpConfigCollectionTransfer(
                    $searchConfig,
                    new SearchHttpConfigCollectionTransfer(),
                );
        } else {
            $this->searchHttpConfigCollectionTransferCache = new SearchHttpConfigCollectionTransfer();
        }

        $this->isSearchHttpConfigCached = true;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function getSearchConfig(): ?array
    {
        return $this->storageClient->get(
            $this->synchronizationService
                ->getStorageKeyBuilder(SearchHttpConfig::SEARCH_HTTP_CONFIG_RESOURCE_NAME)
                ->generateKey(new SynchronizationDataTransfer()),
        );
    }
}
