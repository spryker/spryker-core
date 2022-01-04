<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStoreClientInterface;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductAbstractStorageBySkuReader implements ProductAbstractStorageReaderInterface
{
    /**
     * @var string
     */
    protected const REFERENCE_NAME = 'sku:';

    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductResourceAliasStorageToStorageClientInterface $storageClient,
        ProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService,
        ProductResourceAliasStorageToStoreClientInterface $storeClient
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(string $identifier, string $localeName): ?array
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference(static::REFERENCE_NAME . $identifier)
            ->setLocale($localeName)
            ->setStore($this->storeClient->getCurrentStore()->getNameOrFail());

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
        $mappingResource = $this->storageClient->get($key);
        if (!$mappingResource) {
            return null;
        }

        return $this->storageClient->get($mappingResource['key']);
    }
}
