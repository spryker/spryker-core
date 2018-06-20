<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage\Storage;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface;
use Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductAlternativeStorage\ProductAlternativeStorageConfig;

class ProductAlternativeStorageReader implements ProductAlternativeStorageReaderInterface
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
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeTransfer
    {
        $key = $this->generateKey($concreteSku);
        $productAlternativeStorageData = $this->storageClient->get($key);

        if (!$productAlternativeStorageData) {
            return null;
        }

        return $this->mapToProductAlternativeStorage($productAlternativeStorageData);
    }

    /**
     * @param array $productAlternativeStorageData
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function mapToProductAlternativeStorage(array $productAlternativeStorageData): ProductAlternativeTransfer
    {
        return (new ProductAlternativeTransfer())
            ->fromArray($productAlternativeStorageData, true);
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
            ->getStorageKeyBuilder(ProductAlternativeStorageConfig::PRODUCT_ALTERNATIVE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
