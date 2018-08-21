<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageResourceAliasStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductImageResourceAliasStorage\Dependency\Client\ProductImageResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductImageResourceAliasStorage\Dependency\Service\ProductImageResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

class ProductAbstractImageStorageReader implements ProductAbstractImageStorageReaderInterface
{
    protected const REFERENCE_NAME = 'sku:';

    /**
     * @var \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Client\ProductImageResourceAliasStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Service\ProductImageResourceAliasStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Client\ProductImageResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductImageResourceAliasStorage\Dependency\Service\ProductImageResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductImageResourceAliasStorageToStorageClientInterface $storageClient,
        ProductImageResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductAbstractImageStorageData(string $sku, string $localeName): ?ProductAbstractImageStorageTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference(static::REFERENCE_NAME . $sku)
            ->setLocale($localeName);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductImageStorageConfig::PRODUCT_ABSTRACT_IMAGE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
        $mappingResource = $this->storageClient->get($key);
        if (!$mappingResource) {
            return null;
        }

        $productAbstractImageStorageData = $this->storageClient
            ->get($mappingResource['key']);

        if (!$productAbstractImageStorageData) {
            return null;
        }

        return (new ProductAbstractImageStorageTransfer())->fromArray($productAbstractImageStorageData, true);
    }
}
