<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductListStorage\ProductListStorageConfig;

class ProductListProductConcreteStorageReader implements ProductListProductConcreteStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductListStorageToStorageClientInterface $storageClient,
        ProductListStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null
     */
    public function findProductConcreteProductListStorage(int $idProductConcrete): ?ProductConcreteProductListStorageTransfer
    {
        $key = $this->generateKey($idProductConcrete);
        $productConcreteProductListStorageData = $this->storageClient->get($key);

        if (!$productConcreteProductListStorageData) {
            return null;
        }

        return $this->mapProductConcreteProductListStorage($productConcreteProductListStorageData);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function generateKey(int $idProductConcrete): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProductConcrete);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductListStorageConfig::PRODUCT_LIST_CONCRETE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $productConcreteProductListStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer
     */
    protected function mapProductConcreteProductListStorage(array $productConcreteProductListStorageData): ProductConcreteProductListStorageTransfer
    {
        return (new ProductConcreteProductListStorageTransfer())->fromArray($productConcreteProductListStorageData, true);
    }
}
