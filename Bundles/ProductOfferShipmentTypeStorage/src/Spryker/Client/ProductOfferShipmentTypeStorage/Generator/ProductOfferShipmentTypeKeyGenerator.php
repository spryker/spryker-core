<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage\Generator;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;

class ProductOfferShipmentTypeKeyGenerator implements ProductOfferShipmentTypeKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceInterface
     */
    protected ProductOfferShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @param \Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductOfferShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string $storeName
     *
     * @return string
     */
    public function generateProductOfferShipmentTypeKey(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        string $storeName
    ): string {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setReference($productOfferStorageTransfer->getProductOfferReferenceOrFail());

        return $this
            ->synchronizationService
            ->getStorageKeyBuilder(ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
