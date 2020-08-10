<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\CartCode;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardCartCodeOperationMessageFinder implements GiftCardCartCodeOperationMessageFinderInterface
{
    public const CART_GIFT_CARD_APPLY_SUCCESSFUL = 'cart.giftcard.apply.successful';
    public const CART_GIFT_CARD_APPLY_FAILED = 'cart.giftcard.apply.failed';

    /**
     * @uses \Spryker\Shared\CartCode\CartCodesConfig::MESSAGE_TYPE_SUCCESS
     */
    protected const MESSAGE_TYPE_SUCCESS = 'success';

    /**
     * @uses \Spryker\Shared\CartCode\CartCodesConfig::MESSAGE_TYPE_ERROR
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer
    {
        $giftCardApplySuccessMessageTransfer = $this->findGiftCardApplySuccessMessage($quoteTransfer, $cartCode);
        if ($giftCardApplySuccessMessageTransfer) {
            return $giftCardApplySuccessMessageTransfer;
        }

        $giftCardApplyFailedMessageTransfer = $this->findGiftCardApplyFailedMessage($quoteTransfer, $cartCode);
        if ($giftCardApplyFailedMessageTransfer) {
            return $giftCardApplyFailedMessageTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function findGiftCardApplySuccessMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
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
    protected function findGiftCardApplyFailedMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
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
