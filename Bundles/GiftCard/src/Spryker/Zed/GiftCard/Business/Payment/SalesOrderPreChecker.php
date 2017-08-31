<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\GiftCard\GiftCardConstants;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;

class SalesOrderPreChecker
{

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReader
     */
    protected $giftCardReader;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     */
    public function __construct(GiftCardReaderInterface $giftCardReader)
    {
        $this->giftCardReader = $giftCardReader;
    }

    /**
     * @void
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function precheckSalesOrderGiftCards(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        if (!$this->hasGiftCardPayments($quoteTransfer)) {
            return;
        }

        $validPayments = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!($paymentTransfer->getPaymentProvider() === GiftCardConstants::PROVIDER_NAME)) {
                $validPayments[] = $paymentTransfer;
                continue;
            }

            $paymentTransfer->requireGiftCard();
            $giftCardTransfer = $paymentTransfer->getGiftCard();

            if (!$this->giftCardReader->isUsed($giftCardTransfer)) {
                $validPayments[] = $paymentTransfer;
                continue;
            }

            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' already used');
            $error->setErrorCode(GiftCardConstants::ERROR_GIFT_CARD_ALREADY_USED);

            $checkoutResponse->addError($error);
        }

        $quoteTransfer->setPayments($validPayments);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasGiftCardPayments(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === GiftCardConstants::PROVIDER_NAME) {
                return true;
            }
        }

        return false;
    }

}
