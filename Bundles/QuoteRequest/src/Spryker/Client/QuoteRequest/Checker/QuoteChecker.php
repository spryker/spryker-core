<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Checker;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteRequestInQuoteCheckoutProcess(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getQuoteRequestVersionReference() && $this->isCustomShipmentPriceSet($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCustomShipmentPriceSet(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $isCustomShipmentPriceSet = $itemTransfer->getShipment() &&
                $itemTransfer->getShipment()->getMethod() &&
                $itemTransfer->getShipment()->getMethod()->getSourcePrice();

            if ($isCustomShipmentPriceSet) {
                return true;
            }
        }

        return false;
    }
}
