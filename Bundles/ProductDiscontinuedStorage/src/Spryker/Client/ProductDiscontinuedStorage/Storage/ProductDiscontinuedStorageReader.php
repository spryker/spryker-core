<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Storage;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;

class ProductDiscontinuedStorageReader implements ProductDiscontinuedStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Service\ProductDiscontinuedStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductDiscontinuedStorageToStorageClientInterface $storageClient,
        ProductDiscontinuedStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $concreteSku
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer
    {
        $key = $this->generateKey($concreteSku, $locale);
        $productDiscontinuedStorageData = $this->storageClient->get($key);

        if (!$productDiscontinuedStorageData) {
            return null;
        }

        return $this->mapToProductDiscontinuedStorage($productDiscontinuedStorageData);
    }

    /**
     * @param array $productDiscontinuedStorageData
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer
     */
    protected function mapToProductDiscontinuedStorage(array $productDiscontinuedStorageData): ProductDiscontinuedStorageTransfer
    {
        return (new ProductDiscontinuedStorageTransfer())
            ->fromArray($productDiscontinuedStorageData, true);
    }

    /**
     * @param string $concreteSku
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey(string $concreteSku, string $locale): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setLocale($locale)
            ->setReference($concreteSku);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
