<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\ProductOffer\Reader;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig;

class ProductOfferServiceReader implements ProductOfferServiceReaderInterface
{
    public function __construct(
        protected ProductOfferStorageClientInterface $productOfferStorageClient,
        protected SelfServicePortalConfig $config
    ) {
    }

    /**
     * @param string $sku
     *
     * @return list<string>
     */
    public function getProductOfferReferencesWithServiceShipmentTypes(string $sku): array
    {
        $applicableShipmentTypeKeys = $this->config->getProductOfferServiceAvailabilityShipmentTypeKeys();
        if ($applicableShipmentTypeKeys === []) {
            return [];
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->addProductConcreteSku($sku);

        $productOfferStorageCollectionTransfer = $this->productOfferStorageClient
            ->getProductOfferStoragesBySkus($productOfferStorageCriteriaTransfer);

        $applicableProductOfferReferences = [];
        foreach ($productOfferStorageCollectionTransfer->getProductOffers() as $productOfferStorageTransfer) {
            if (!$this->hasApplicableShipmentType($productOfferStorageTransfer, $applicableShipmentTypeKeys)) {
                continue;
            }

            $applicableProductOfferReferences[] = $productOfferStorageTransfer->getProductOfferReferenceOrFail();
        }

        return $applicableProductOfferReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorage
     * @param list<string> $applicableShipmentTypeKeys
     *
     * @return bool
     */
    protected function hasApplicableShipmentType(ProductOfferStorageTransfer $productOfferStorage, array $applicableShipmentTypeKeys): bool
    {
        $shipmentTypeStorageTransfers = $productOfferStorage->getShipmentTypes();
        if ($shipmentTypeStorageTransfers->count() === 0) {
            return false;
        }

        foreach ($shipmentTypeStorageTransfers as $shipmentTypeTransfer) {
            if (in_array($shipmentTypeTransfer->getKey(), $applicableShipmentTypeKeys, true)) {
                return true;
            }
        }

        return false;
    }
}
