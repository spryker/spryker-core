<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander;

use ArrayObject;

class ProductOfferServicePointAvailabilityRequestItemsExpander implements ProductOfferServicePointAvailabilityRequestItemsExpanderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer>
     */
    public function expandWithIdentifier(
        ArrayObject $productOfferServicePointAvailabilityRequestItemTransfers
    ): ArrayObject {
        foreach ($productOfferServicePointAvailabilityRequestItemTransfers as $key => $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferServicePointAvailabilityRequestItemTransfer->setIdentifier($key);
        }

        return $productOfferServicePointAvailabilityRequestItemTransfers;
    }
}
