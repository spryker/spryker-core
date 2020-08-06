<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\CartCode;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class VoucherCartCodeAdder implements VoucherCartCodeAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        if ($this->isCartCodeInQuote($quoteTransfer, $cartCode)) {
            return $quoteTransfer;
        }

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($cartCode);

        return $quoteTransfer->addVoucherDiscount($voucherDiscount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return bool
     */
    protected function isCartCodeInQuote(QuoteTransfer $quoteTransfer, string $cartCode): bool
    {
        foreach ($quoteTransfer->getVoucherDiscounts() as $voucherDiscount) {
            if ($voucherDiscount->getVoucherCode() === $cartCode) {
                return true;
            }
        }

        return false;
    }
}
