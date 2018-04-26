<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Storage;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductQuantityStorage\ProductQuantityStorageConfig;

class ProductQuantityStorageReader implements ProductQuantityStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductQuantityStorageToStorageClientInterface $storageClient,
        ProductQuantityStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    public function findProductQuantityStorage(int $idProduct): ?ProductQuantityStorageTransfer
    {
        $key = $this->generateKey($idProduct);
        $productQuantityStorageData = $this->storageClient->get($key);

        if (!$productQuantityStorageData) {
            return null;
        }

        return $this->mapToProductQuantityStorageTransfer($productQuantityStorageData);
    }

    /**
     * @param array $productQuantityStorageData
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function mapToProductQuantityStorageTransfer(array $productQuantityStorageData): ProductQuantityStorageTransfer
    {
        return (new ProductQuantityStorageTransfer())
            ->fromArray($productQuantityStorageData, true);
    }

    /**
     * @param int $idProduct
     *
     * @return string
     */
    protected function generateKey(int $idProduct): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProduct);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductQuantityStorageConfig::PRODUCT_QUANTITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
