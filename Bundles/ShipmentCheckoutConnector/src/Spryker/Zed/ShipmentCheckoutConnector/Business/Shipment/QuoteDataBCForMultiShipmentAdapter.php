<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * @deprecated Will be removed in next major release.
 */
class QuoteDataBCForMultiShipmentAdapter implements QuoteDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function adapt(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->assertThatItemTransfersHaveShipmentAndShipmentMethod($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipmentMethod($quoteTransfer)) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShipmentMethod($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentMethodForBC($itemTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransfersHaveShipmentAndShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getMethod() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment()->getMethod() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShipmentMethod(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getMethod() !== null);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        return $quoteTransfer->getShipment();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentMethodForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer->setMethod($quoteTransfer->getShipment()->getMethod());
        $itemTransfer->setShipment($shipmentTransfer);
    }
}
