<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteInQuoteRequestProcess(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getQuoteRequestVersionReference() && $this->isShipmentSourcePriceSet($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSourcePriceSet(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->extractShipmentSourcePrice($itemTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function extractShipmentSourcePrice(ItemTransfer $itemTransfer): ?MoneyValueTransfer
    {
        $shipmentMethodTransfer = $itemTransfer->getShipment() ? $itemTransfer->getShipment()->getMethod() : null;

        if (!$shipmentMethodTransfer) {
            return null;
        }

        return $shipmentMethodTransfer->getSourcePrice();
    }
}
