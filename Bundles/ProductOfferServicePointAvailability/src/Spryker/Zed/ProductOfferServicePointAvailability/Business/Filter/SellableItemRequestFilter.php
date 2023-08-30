<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\SellableItemRequestTransfer;

class SellableItemRequestFilter implements SellableItemRequestFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemRequestTransfer> $sellableItemRequestTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemRequestTransfer>
     */
    public function filterSellableItemRequestTransfersWithProductOfferReferenceAndServicePoint(
        ArrayObject $sellableItemRequestTransfers
    ): ArrayObject {
        $filteredSellableItemRequestTransfers = [];
        foreach ($sellableItemRequestTransfers as $sellableItemRequestTransfer) {
            if (!$this->hasProductOfferReferenceAndServicePoint($sellableItemRequestTransfer)) {
                continue;
            }

            $filteredSellableItemRequestTransfers[] = $sellableItemRequestTransfer;
        }

        return new ArrayObject($filteredSellableItemRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     *
     * @return bool
     */
    protected function hasProductOfferReferenceAndServicePoint(SellableItemRequestTransfer $sellableItemRequestTransfer): bool
    {
        $productAvailabilityCriteriaTransfer = $sellableItemRequestTransfer->getProductAvailabilityCriteria();

        if (!$productAvailabilityCriteriaTransfer) {
            return false;
        }

        return $productAvailabilityCriteriaTransfer->getProductOfferReference() && $productAvailabilityCriteriaTransfer->getServicePoint();
    }
}
