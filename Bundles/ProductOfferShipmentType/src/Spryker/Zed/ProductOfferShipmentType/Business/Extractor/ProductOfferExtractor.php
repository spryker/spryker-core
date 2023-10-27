<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Extractor;

use ArrayObject;

class ProductOfferExtractor implements ProductOfferExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<string>
     */
    public function extractShipmentTypeUuidsFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $shipmentTypeUuids = [];

        foreach ($productOfferTransfers as $productOfferTransfer) {
            foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeUuids[] = $shipmentTypeTransfer->getUuidOrFail();
            }
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<int>
     */
    public function extractShipmentTypeIdsFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $shipmentTypeUuids = [];

        foreach ($productOfferTransfers as $productOfferTransfer) {
            foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeUuids[] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            }
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<int>
     */
    public function extractProductOfferIdsFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $productOfferIds = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $productOfferIds[] = $productOfferTransfer->getIdProductOfferOrFail();
        }

        return $productOfferIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<string>
     */
    public function extractProductOfferReferencesFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $productOfferReferences = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $productOfferReferences[] = $productOfferTransfer->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }
}
