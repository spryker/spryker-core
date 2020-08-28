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
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface;
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
     * @var \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductOfferAvailabilityStorageToStorageClientInterface $storageClient,
        ProductOfferAvailabilityStorageToSynchronizationServiceInterface $synchronizationService,
        ProductOfferAvailabilityStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
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
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[]
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array
    {
        $productOfferAvailabilityStorageKeys = $this->generateKeys($productOfferReferences, $storeName);
        $productOfferAvailabilityStorageTransferData = $this->storageClient->getMulti(
            $productOfferAvailabilityStorageKeys
        );

        $productOfferAvailabilityStorageTransfers = [];
        foreach ($productOfferAvailabilityStorageTransferData as $storageKey => $productOfferAvailabilityStorageTransferDataItem) {
            $decodedProductOfferAvailabilityStorageTransferDataItem = $this->utilEncodingService
                ->decodeJson($productOfferAvailabilityStorageTransferDataItem, true);

            if (!$decodedProductOfferAvailabilityStorageTransferDataItem) {
                continue;
            }

            $storageKeyParts = explode(':', $storageKey);
            $productOfferReferences = end($storageKeyParts);

            $productOfferAvailabilityStorageTransfers[$productOfferReferences] = $this->mapToProductOfferAvailabilityStorageDataToTransfer(
                $decodedProductOfferAvailabilityStorageTransferDataItem,
                new ProductOfferAvailabilityStorageTransfer()
            );
        }

        return $productOfferAvailabilityStorageTransfers;
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
        $productOfferAvailabilityStorageTransfer->fromArray($productOfferAvailabilityStorageTransferData, true);

        $productOfferAvailabilityStorageTransfer->setIsAvailable(
            $this->isProductOfferAvailable($productOfferAvailabilityStorageTransfer)
        );

        return $productOfferAvailabilityStorageTransfer;
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return string[]
     */
    protected function generateKeys(array $productOfferReferences, string $storeName): array
    {
        $productOfferAvailabilityStorageKeys = [];

        foreach ($productOfferReferences as $productOfferReference) {
            $productOfferAvailabilityStorageKeys[] = $this->generateKey($productOfferReference, $storeName);
        }

        return $productOfferAvailabilityStorageKeys;
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

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     *
     * @return bool
     */
    protected function isProductOfferAvailable(ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer): bool
    {
        $availability = $productOfferAvailabilityStorageTransfer->getAvailability();
        $isAvailable = $availability && $availability->isPositive();

        return $productOfferAvailabilityStorageTransfer->getIsNeverOutOfStock() || $isAvailable;
    }
}
