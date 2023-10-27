<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;

class ProductOfferServicePointAvailabilitySanitizer implements ProductOfferServicePointAvailabilitySanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function sanitizeProductOfferStorage(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            $productOfferServicePointAvailabilityResponseItemTransfer->setProductOfferStorage(null);
        }

        return $productOfferServicePointAvailabilityCollectionTransfer;
    }
}
