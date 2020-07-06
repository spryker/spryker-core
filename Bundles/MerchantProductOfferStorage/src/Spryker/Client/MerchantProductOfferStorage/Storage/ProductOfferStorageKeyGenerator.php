<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

class ProductOfferStorageKeyGenerator implements ProductOfferStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService,
        MerchantProductOfferStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return string[]
     */
    public function generateProductConcreteProductOffersKeys(array $productConcreteSkus): array
    {
        $storageKeys = [];

        foreach ($productConcreteSkus as $productConcreteSku) {
            $storageKeys[] = $this->generateKey($productConcreteSku, MerchantProductOfferStorageConfig::RESOURCE_PRODUCT_CONCRETE_PRODUCT_OFFERS_NAME);
        }

        return $storageKeys;
    }

    /**
     * @param string[] $merchantProductOfferReferences
     *
     * @return string[]
     */
    public function generateMerchantProductOfferKeys(array $merchantProductOfferReferences): array
    {
        $storageKeys = [];

        foreach ($merchantProductOfferReferences as $merchantProductOfferReference) {
            $storageKeys[] = $this->generateKey($merchantProductOfferReference, MerchantProductOfferStorageConfig::RESOURCE_MERCHANT_PRODUCT_OFFER_NAME);
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
