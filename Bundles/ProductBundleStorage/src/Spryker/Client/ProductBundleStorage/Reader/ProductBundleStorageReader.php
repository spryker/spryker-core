<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Reader;

use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface;
use Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig;

class ProductBundleStorageReader implements ProductBundleStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductBundleStorageToStorageClientInterface $storageClient,
        ProductBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ProductBundleStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array
    {
        $productBundleStorageData = $this->storageClient->getMulti(
            $this->generateKeys($productConcreteIds)
        );

        if (!$productBundleStorageData) {
            return [];
        }

        $productBundleStorageTransfers = [];
        foreach ($productBundleStorageData as $productBundleStorageDatum) {
            $decodedProductBundleStorageData = $this->utilEncodingService
                ->decodeJson($productBundleStorageDatum, true);

            if (!is_array($decodedProductBundleStorageData)) {
                continue;
            }

            $productBundleStorageTransfer = (new ProductBundleStorageTransfer())
                ->fromArray($decodedProductBundleStorageData, true);

            if (!$productBundleStorageTransfer->getIdProductConcreteBundle()) {
                continue;
            }

            $productBundleStorageTransfers[$productBundleStorageTransfer->getIdProductConcreteBundle()] = $productBundleStorageTransfer;
        }

        return $productBundleStorageTransfers;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return string[]
     */
    protected function generateKeys(array $productConcreteIds): array
    {
        $productBundleStorageKeys = [];
        foreach ($productConcreteIds as $idProductConcrete) {
            $productBundleStorageKeys[] = $this->generateKey($idProductConcrete);
        }

        return $productBundleStorageKeys;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function generateKey(int $idProductConcrete): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference((string)$idProductConcrete);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductBundleStorageConfig::PRODUCT_BUNDLE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
