<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval\Plugin\Permission\ContextProvider;

use Generated\Shared\Transfer\QuoteTransfer;

class PermissionContextProvider implements PermissionContextProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return array|null
     */
    public function provideContext(?QuoteTransfer $quoteTransfer): ?array
    {
        if ($quoteTransfer === null) {
            return null;
        }

        return [
            static::CENT_AMOUNT => $this->getQuoteSum($quoteTransfer),
            static::STORE_NAME => $quoteTransfer->getStore()->getName(),
            static::CURRENCY_CODE => $quoteTransfer->getCurrency()->getCode(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getQuoteSum(QuoteTransfer $quoteTransfer): int
    {
        return $quoteTransfer->getTotals()->getGrandTotal() - $this->getShipmentPriceForQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipmentPriceForQuote(QuoteTransfer $quoteTransfer): int
    {
        if (!$quoteTransfer->getShipment()) {
            return 0;
        }

        $shipmentMethodTransfer = $quoteTransfer->getShipment()
            ->getMethod();

        if (!$shipmentMethodTransfer) {
            return 0;
        }

        return $shipmentMethodTransfer->getStoreCurrencyPrice();
    }
}
