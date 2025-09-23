<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\ProductOffer\Checker;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Client\SelfServicePortal\ProductOffer\Reader\ProductOfferServiceReaderInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig;

class ProductServiceAvailabilityChecker implements ProductServiceAvailabilityCheckerInterface
{
    public function __construct(
        protected ProductOfferServiceReaderInterface $productOfferServiceReader,
        protected ProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient,
        protected StoreClientInterface $storeClient,
        protected SelfServicePortalConfig $config
    ) {
    }

    public function isApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return false;
        }

        if (!$productViewTransfer->getSku()) {
            return false;
        }

        if ($productViewTransfer->getProductOfferReference()) {
            return false;
        }

        if (!$this->hasServiceProductClass($productViewTransfer)) {
            return false;
        }

        if (!$this->hasApplicableShipmentTypes($productViewTransfer)) {
            return false;
        }

        $applicableOfferReferences = $this->productOfferServiceReader->getProductOfferReferencesWithServiceShipmentTypes($productViewTransfer->getSkuOrFail());

        return $applicableOfferReferences !== [];
    }

    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        if (!$this->isApplicable($productViewTransfer)) {
            return false;
        }

        $applicableOfferReferences = $this->productOfferServiceReader->getProductOfferReferencesWithServiceShipmentTypes($productViewTransfer->getSkuOrFail());
        if ($applicableOfferReferences === []) {
            return false;
        }

        return $this->hasAvailableOffers($applicableOfferReferences);
    }

    protected function hasServiceProductClass(ProductViewTransfer $productViewTransfer): bool
    {
        $serviceProductClassName = $this->config->getServiceProductClassName();
        $productClassNames = $productViewTransfer->getProductClassNames();

        return in_array($serviceProductClassName, $productClassNames, true);
    }

    protected function hasApplicableShipmentTypes(ProductViewTransfer $productViewTransfer): bool
    {
        $applicableShipmentTypeKeys = $this->config->getProductOfferServiceAvailabilityShipmentTypeKeys();
        if ($applicableShipmentTypeKeys === []) {
            return false;
        }

        $shipmentTypeStorageTransfers = $productViewTransfer->getShipmentTypes();
        if ($shipmentTypeStorageTransfers->count() === 0) {
            return false;
        }

        foreach ($shipmentTypeStorageTransfers as $shipmentTypeTransfer) {
            if (in_array($shipmentTypeTransfer->getKeyOrFail(), $applicableShipmentTypeKeys, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<string> $productOfferReferences
     *
     * @return bool
     */
    protected function hasAvailableOffers(array $productOfferReferences): bool
    {
        $storeName = $this->storeClient->getCurrentStore()->getNameOrFail();
        $productOfferAvailabilityStorageTransfers = $this->productOfferAvailabilityStorageClient
            ->getByProductOfferReferences($productOfferReferences, $storeName);

        foreach ($productOfferAvailabilityStorageTransfers as $productOfferAvailabilityStorage) {
            if ($productOfferAvailabilityStorage->getIsAvailable()) {
                return true;
            }
        }

        return false;
    }
}
