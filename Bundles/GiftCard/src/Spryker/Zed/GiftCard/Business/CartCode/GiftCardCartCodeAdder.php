<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\CartCode;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardCartCodeAdder implements GiftCardCartCodeAdderInterface
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

        $giftCard = new GiftCardTransfer();
        $giftCard->setCode($cartCode);

        return $quoteTransfer->addGiftCard($giftCard);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return bool
     */
    protected function isCartCodeInQuote(QuoteTransfer $quoteTransfer, string $cartCode): bool
    {
        foreach ($quoteTransfer->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() === $cartCode) {
                return true;
            }
        }

        return false;
    }
}
