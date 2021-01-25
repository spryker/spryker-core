<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Checker;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isEditableQuoteRequestVersion(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getQuoteRequestReference() && !$quoteTransfer->getQuoteRequestVersionReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isEditableQuoteShipmentSourcePrice(QuoteTransfer $quoteTransfer): bool
    {
        return !$quoteTransfer->getQuoteRequestReference() && $this->isShipmentSourcePriceSet($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSourcePriceSet(QuoteTransfer $quoteTransfer): bool
    {
        $shipmentTransfer = $this->findQuoteLevelShipment($quoteTransfer);
        if ($shipmentTransfer) {
            return (bool)$this->extractShipmentSourcePrice($shipmentTransfer);
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            if ($shipmentTransfer && $this->extractShipmentSourcePrice($shipmentTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function findQuoteLevelShipment(QuoteTransfer $quoteTransfer): ?ShipmentTransfer
    {
        $shipmentTransfer = $quoteTransfer->getShipment();
        if (!$shipmentTransfer || !$shipmentTransfer->getMethod()) {
            return null;
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === static::SHIPMENT_EXPENSE_TYPE) {
                return $expenseTransfer->getShipment();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function extractShipmentSourcePrice(ShipmentTransfer $shipmentTransfer): ?MoneyValueTransfer
    {
        if ($shipmentTransfer->getMethod()) {
            return $shipmentTransfer->getMethod()->getSourcePrice();
        }

        return null;
    }
}
