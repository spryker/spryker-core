<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface;
use Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;

class ProductOfferStorageKeyGenerator implements ProductOfferStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductOfferStorageToSynchronizationServiceInterface $synchronizationService,
        ProductOfferStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<string>
     */
    public function generateProductConcreteProductOffersKeys(array $productConcreteSkus): array
    {
        $storageKeys = [];

        foreach ($productConcreteSkus as $productConcreteSku) {
            $storageKeys[] = $this->generateKey($productConcreteSku, ProductOfferStorageConfig::RESOURCE_PRODUCT_CONCRETE_PRODUCT_OFFERS_NAME);
        }

        return $storageKeys;
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<string>
     */
    public function generateProductOfferKeys(array $productOfferReferences): array
    {
        $storageKeys = [];

        foreach ($productOfferReferences as $productOfferReference) {
            $storageKeys[] = $this->generateKey($productOfferReference, ProductOfferStorageConfig::RESOURCE_PRODUCT_OFFER_NAME);
        }

        return $storageKeys;
    }

    /**
     * @param string $keyName
     * @param string $resourceName
     *
     * @return string
     */
    public function generateKey(string $keyName, string $resourceName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setStore($this->storeClient->getCurrentStore()->getName());

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
