<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Expander;

use Generated\Shared\Transfer\QuoteTransfer;

class ShipmentExpander implements ShipmentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandShipmentWithMerchantReference(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment()) {
                continue;
            }

            $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());
        }

        return $quoteTransfer;
    }
}
