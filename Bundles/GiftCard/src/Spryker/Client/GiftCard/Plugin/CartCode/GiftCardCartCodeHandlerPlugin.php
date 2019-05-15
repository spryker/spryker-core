<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GiftCard\Plugin\CartCode;

use Generated\Shared\Transfer\CartCodeOperationMessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\GiftCard\GiftCardFactory getFactory()
 */
class GiftCardCartCodeHandlerPlugin extends AbstractPlugin implements CartCodeHandlerPluginInterface
{
    public const CART_GIFT_CARD_APPLY_SUCCESSFUL = 'cart.giftcard.apply.successful';
    public const CART_GIFT_CARD_APPLY_FAILED = 'cart.giftcard.apply.failed';

    /**
     * {@inheritdoc}
     * - Sets gift card to the quote if the code hasn't been added already.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeHandler()
            ->addCandidate($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Removes matching applied gift card and gift card payment from quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeHandler()
            ->removeCode($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Returns gift card apply success message in case the given gift card code has been applied successfully.
     * - Returns gift card apply failed message in case the given gift card code hasn't been applied successfully.
     * - Returns an empty failed message if code is not relevant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationMessageTransfer
     */
    public function getCartCodeOperationResult(QuoteTransfer $quoteTransfer, $code): CartCodeOperationMessageTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeHandler()
            ->getCartCodeOperationResult($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Clears all gift cards and gift card payments from the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeHandler()
            ->clearAllCodes($quoteTransfer);
    }
}
