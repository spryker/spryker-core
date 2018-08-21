<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductCategoryStorage\ProductCategoryStorageConfig;

class ProductAbstractCategoryStorageReader implements ProductAbstractCategoryStorageReaderInterface
{
    protected const REFERENCE_NAME = 'sku:';

    /**
     * @var \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductCategoryResourceAliasStorageToStorageClientInterface $storageClient,
        ProductCategoryResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategoryStorageData(string $sku, string $localeName): ?ProductAbstractCategoryStorageTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference(static::REFERENCE_NAME . $sku)
            ->setLocale($localeName);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductCategoryStorageConfig::PRODUCT_ABSTRACT_CATEGORY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
        $mappingResource = $this->storageClient->get($key);
        if (!$mappingResource) {
            return null;
        }

        $productAbstractCategoryStorageData = $this->storageClient
            ->get($mappingResource['key']);

        if (!$productAbstractCategoryStorageData) {
            return null;
        }

        return (new ProductAbstractCategoryStorageTransfer())->fromArray($productAbstractCategoryStorageData, true);
    }
}
