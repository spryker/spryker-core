<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Generator;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig;

class ProductOfferServiceStorageKeyGenerator implements ProductOfferServiceStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToSynchronizationServiceInterface
     */
    protected ProductOfferServicePointStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductOfferServicePointStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param list<string> $productOfferReferences
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateKeys(array $productOfferReferences, string $storeName): array
    {
        $storageKeys = [];
        foreach ($productOfferReferences as $productOfferReference) {
            $storageKeys[] = $this->generateKey($productOfferReference, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param string $reference
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $reference, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOfferServicePointStorageConfig::PRODUCT_OFFER_SERVICE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
