<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductConcreteStorageBySkuReader implements ProductConcreteStorageReaderInterface
{
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
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ProductResourceAliasStorageToStorageClientInterface $storageClient,
        ProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(string $sku, string $localeName): ?array
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference(static::REFERENCE_NAME . $sku)
            ->setLocale($localeName);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_CONCRETE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
        $mappingResource = $this->storageClient->get($key);
        if (!$mappingResource) {
            return null;
        }

        return $this->storageClient->get($mappingResource['key']);
    }
}
