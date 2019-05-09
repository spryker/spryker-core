<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GiftCard\Plugin\CartCode;

use ArrayObject;
use Generated\Shared\Transfer\CodeCalculationErrorTransfer;
use Generated\Shared\Transfer\CodeCalculationResultTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

class GiftCardCartCodeHandlerPlugin extends AbstractPlugin implements CartCodeHandlerPluginInterface
{
    public const CART_GIFT_CARD_APPLY_SUCCESSFUL = 'cart.giftcard.apply.successful';
    public const CART_GIFT_CARD_APPLY_FAILED = 'cart.giftcard.apply.failed';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code)
    {
        if ($this->hasCandidate($quoteTransfer, $code)) {
            return;
        }

        $giftCard = new GiftCardTransfer();
        $giftCard->setCode($code);

        $quoteTransfer->addGiftCard($giftCard);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code)
    {
        $this->removeGiftCard($quoteTransfer, $code);
        $this->removeGiftCardPayment($quoteTransfer, $code);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return void
     */
    protected function removeGiftCard(QuoteTransfer $quoteTransfer, $code)
    {
        $giftCardTransferCollection = $quoteTransfer->getGiftCards();

        foreach ($giftCardTransferCollection as $index => $giftCardTransfer) {
            if ($giftCardTransfer->getCode() === $code) {
                $giftCardTransferCollection->offsetUnset($index);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeGiftCardPayment(QuoteTransfer $quoteTransfer, ?string $code = null)
    {
        foreach ($quoteTransfer->getPayments() as $index => $payment) {
            if ($payment->getGiftCard() && $code === null || $payment->getGiftCard()->getCode() === $code) {
                $quoteTransfer->getPayments()->offsetUnset($index);
            }
        }

        $quoteTransfer->setPayment(null);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CodeCalculationResultTransfer
     */
    public function getCartCodeRecalculationResult(QuoteTransfer $quoteTransfer, $code)
    {
        $result = new CodeCalculationResultTransfer();
        $result->setIsSuccess(false);
        $result->setCode($code);

        foreach ($quoteTransfer->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() === $code) {
                $result->setIsSuccess(true);

                return $result;
            }
        }

        foreach ($quoteTransfer->getNotApplicableGiftCardCodes() as $giftCardCode) {
            if ($giftCardCode === $code) {
                $errorTransfer = new CodeCalculationErrorTransfer();
                $errorTransfer->setMessage(static::CART_GIFT_CARD_APPLY_FAILED);

                $result->addError($errorTransfer);

                break;
            }
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function clearQuote(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->setGiftCards(new ArrayObject());

        $this->removeGiftCardPayment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return string
     */
    public function getSuccessMessage(QuoteTransfer $quoteTransfer, $code)
    {
        return static::CART_GIFT_CARD_APPLY_SUCCESSFUL;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    public function hasCandidate(QuoteTransfer $quoteTransfer, $code)
    {
        foreach ($quoteTransfer->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() === $code) {
                return true;
            }
        }

        return false;
    }
}
