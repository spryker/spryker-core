<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Calculator;

use Generated\Shared\Transfer\AddressTransfer;
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
        if ($this->assertThatItemTransfersHaveShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoAddressTransfer($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShippingAddress($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentAddressForBC($itemTransfer, $quoteTransfer);
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
    protected function assertThatItemTransfersHaveShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getShippingAddress() === null) {
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
    protected function assertThatQuoteHasNoAddressTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress() === null;
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShippingAddress(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getShippingAddress() !== null);
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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShipmentAddressTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): AddressTransfer
    {
        if ($itemTransfer->getShipment()->getShippingAddress() !== null) {
            return $itemTransfer->getShipment()->getShippingAddress();
        }

        return $quoteTransfer->getShipment()->getShippingAddress();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentAddressForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void
    {
        $shippingAddressTransfer = $this->getShipmentAddressTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);
        $itemTransfer->setShipment($shipmentTransfer);
    }
}
