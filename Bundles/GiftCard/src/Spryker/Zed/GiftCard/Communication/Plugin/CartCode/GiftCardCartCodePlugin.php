<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\CartCode;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getFactory()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacade getFacade()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 */
class GiftCardCartCodePlugin extends AbstractPlugin implements CartCodePluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets gift card to the quote if the code hasn't been added already.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        return $this->getFacade()->addCartCode($quoteTransfer, $cartCode);
    }

    /**
     * {@inheritDoc}
     * - Removes matching applied gift card and gift card payment from quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        return $this->getFacade()->removeCartCode($quoteTransfer, $cartCode);
    }

    /**
     * {@inheritDoc}
     * - Clears all gift cards and gift card payments from the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->clearCartCodes($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns gift card apply success message in case the given gift card code has been applied successfully.
     * - Returns gift card apply failed message in case the given gift card code hasn't been applied successfully.
     * - Returns an empty failed message if code is not relevant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer
    {
        return $this->getFacade()->findOperationResponseMessage($quoteTransfer, $cartCode);
    }
}
