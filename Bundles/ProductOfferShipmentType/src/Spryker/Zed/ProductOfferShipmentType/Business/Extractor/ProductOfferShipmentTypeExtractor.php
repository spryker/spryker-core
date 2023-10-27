<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Extractor;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;

class ProductOfferShipmentTypeExtractor implements ProductOfferShipmentTypeExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return list<int>
     */
    public function extractShipmentTypeIdsFromProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): array {
        $shipmentTypeIds = [];
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            foreach ($productOfferShipmentTypeTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeIds[] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            }
        }

        return $shipmentTypeIds;
    }
}
