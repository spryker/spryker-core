<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductListProductAbstractStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductListStorage\ProductListStorageConfig;

class ProductListProductAbstractStorageReader implements ProductListProductAbstractStorageReaderInterface
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer|null
     */
    public function findProductAbstractProductListStorage(int $idProductAbstract): ?ProductAbstractProductListStorageTransfer
    {
        $key = $this->generateKey($idProductAbstract);
        $productAbstractProductListStorageData = $this->storageClient->get($key);

        if (!$productAbstractProductListStorageData) {
            return null;
        }

        return $this->mapProductAbstractProductListStorage($productAbstractProductListStorageData);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateKey(int $idProductAbstract): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductListStorageConfig::PRODUCT_LIST_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $productAbstractProductListStorageData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer
     */
    protected function mapProductAbstractProductListStorage(array $productAbstractProductListStorageData): ProductAbstractProductListStorageTransfer
    {
        return (new ProductAbstractProductListStorageTransfer())->fromArray($productAbstractProductListStorageData, true);
    }
}
