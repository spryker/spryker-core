<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Reader;

use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;
use Spryker\Client\SearchHttp\Builder\ConfigKeyBuilderInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface;
use Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface
     */
    protected SearchHttpToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\SearchHttp\Builder\ConfigKeyBuilderInterface
     */
    protected ConfigKeyBuilderInterface $configKeyBuilder;

    /**
     * @var \Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface
     */
    protected ConfigMapperInterface $searchHttpConfigMapper;

    /**
     * @var bool
     */
    protected bool $isSearchHttpConfigCached;

    /**
     * @var \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    protected SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransferCache;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface $storageClient
     * @param \Spryker\Client\SearchHttp\Builder\ConfigKeyBuilderInterface $configKeyBuilder
     * @param \Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface $searchHttpConfigMapper
     */
    public function __construct(
        SearchHttpToStorageClientInterface $storageClient,
        ConfigKeyBuilderInterface $configKeyBuilder,
        ConfigMapperInterface $searchHttpConfigMapper
    ) {
        $this->storageClient = $storageClient;
        $this->configKeyBuilder = $configKeyBuilder;
        $this->searchHttpConfigMapper = $searchHttpConfigMapper;

        $this->isSearchHttpConfigCached = false;
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
     * @return void
     */
    protected function loadSearchConfigForCurrentStore(): void
    {
        if ($this->isSearchHttpConfigCached) {
            return;
        }

        $searchConfig = $this->getSearchConfigForCurrentStore();

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
    protected function getSearchConfigForCurrentStore(): ?array
    {
        return $this->storageClient->get($this->configKeyBuilder->buildKeyForCurrentStore());
    }
}
