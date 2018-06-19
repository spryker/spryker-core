<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\Storage;

use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface;
use Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductAlternativeStorage\ProductAlternativeStorageConfig;

class ProductReplacementStorageReader implements ProductReplacementStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductAlternativeStorageToStorageClientInterface $storageClient,
        ProductAlternativeStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductReplacementStorageTransfer
    {
        $key = $this->generateKey($concreteSku);
        $productReplacementStorageData = $this->storageClient->get($key);

        if (!$productReplacementStorageData) {
            return null;
        }

        return $this->mapToProductAlternativeStorage($productReplacementStorageData);
    }

    /**
     * @param array $productReplacementStorageData
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer
     */
    protected function mapToProductAlternativeStorage(array $productReplacementStorageData): ProductReplacementStorageTransfer
    {
        return (new ProductReplacementStorageTransfer())
            ->fromArray($productReplacementStorageData, true);
    }

    /**
     * @param string $concreteSku
     *
     * @return string
     */
    protected function generateKey(string $concreteSku): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($concreteSku);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductAlternativeStorageConfig::PRODUCT_REPLACEMENT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
