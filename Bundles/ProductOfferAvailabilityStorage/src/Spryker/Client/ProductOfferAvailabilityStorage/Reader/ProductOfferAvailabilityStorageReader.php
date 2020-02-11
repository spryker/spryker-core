<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Reader;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;

class ProductOfferAvailabilityStorageReader implements ProductOfferAvailabilityStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductOfferAvailabilityStorageToStorageClientInterface $storageClient,
        ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer|null
     */
    public function findByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer
    {
        $productOfferAvailabilityStorageTransferData = $this->storageClient->get(
            $this->generateKey($productOfferReference, $storeName)
        );

        if (!$productOfferAvailabilityStorageTransferData) {
            return null;
        }

        return $this->mapToProductOfferAvailabilityStorageDataToTransfer(
            $productOfferAvailabilityStorageTransferData,
            new ProductOfferAvailabilityStorageTransfer()
        );
    }

    /**
     * @param array $productOfferAvailabilityStorageTransferData
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer
     */
    protected function mapToProductOfferAvailabilityStorageDataToTransfer(
        array $productOfferAvailabilityStorageTransferData,
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
    ): ProductOfferAvailabilityStorageTransfer {
        return $productOfferAvailabilityStorageTransfer->fromArray($productOfferAvailabilityStorageTransferData, true);
    }

    /**
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $productOfferReference, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($productOfferReference)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
