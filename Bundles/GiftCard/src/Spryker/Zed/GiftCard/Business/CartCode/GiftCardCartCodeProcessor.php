<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\CartCode;

use ArrayObject;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardCartCodeProcessor implements GiftCardCartCodeProcessorInterface
{
    public const CART_GIFT_CARD_APPLY_SUCCESSFUL = 'cart.giftcard.apply.successful';
    public const CART_GIFT_CARD_APPLY_FAILED = 'cart.giftcard.apply.failed';

    protected const MESSAGE_TYPE_SUCCESS = 'success';
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        if ($this->hasCandidate($quoteTransfer, $cartCode)) {
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        $quoteTransfer = $this->removeGiftCard($quoteTransfer, $cartCode);

        return $this->removeGiftCardPayment($quoteTransfer, $cartCode);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->removeGiftCardPayment($quoteTransfer->setGiftCards(new ArrayObject()));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function getOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer
    {
        $giftCardApplySuccessMessageTransfer = $this->getGiftCardApplySuccessMessage($quoteTransfer, $cartCode);
        if ($giftCardApplySuccessMessageTransfer) {
            return $giftCardApplySuccessMessageTransfer;
        }

        $giftCardApplyFailedMessageTransfer = $this->getGiftCardApplyFailedMessage($quoteTransfer, $cartCode);
        if ($giftCardApplyFailedMessageTransfer) {
            return $giftCardApplyFailedMessageTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeGiftCard(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        $giftCardTransferCollection = $quoteTransfer->getGiftCards();

        foreach ($giftCardTransferCollection as $index => $giftCardTransfer) {
            if ($giftCardTransfer->getCode() === $code) {
                $giftCardTransferCollection->offsetUnset($index);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeGiftCardPayment(QuoteTransfer $quoteTransfer, ?string $code = null): QuoteTransfer
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
     * @return bool
     */
    protected function hasCandidate(QuoteTransfer $quoteTransfer, string $code): bool
    {
        foreach ($quoteTransfer->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function getGiftCardApplySuccessMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        foreach ($quoteTransfer->getGiftCards() as $giftCard) {
            if ($giftCard->getCode() !== $code) {
                continue;
            }

            $messageTransfer = new MessageTransfer();
            $messageTransfer
                ->setValue(static::CART_GIFT_CARD_APPLY_SUCCESSFUL)
                ->setType(static::MESSAGE_TYPE_SUCCESS);

            return $messageTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function getGiftCardApplyFailedMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        foreach ($quoteTransfer->getNotApplicableGiftCardCodes() as $giftCardCode) {
            if ($giftCardCode !== $code) {
                continue;
            }

            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue(static::CART_GIFT_CARD_APPLY_FAILED);
            $messageTransfer->setType(static::MESSAGE_TYPE_ERROR);

            return $messageTransfer;
        }

        return null;
    }
}
