<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentsRestApi\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ShipmentExpander implements ShipmentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteShipmentWithMerchantReference(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->expandItemShipmentWithMerchantReference($itemTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemShipmentWithMerchantReference(ItemTransfer $itemTransfer): ItemTransfer
    {
        $shipmentTransfer = $itemTransfer->getShipment();

        if (!$shipmentTransfer) {
            return $itemTransfer;
        }

        $shipmentTransfer->setMerchantReference($itemTransfer->getMerchantReference());

        return $itemTransfer;
    }
}
