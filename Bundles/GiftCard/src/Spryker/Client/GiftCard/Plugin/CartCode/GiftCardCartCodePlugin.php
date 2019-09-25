<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GiftCard\Plugin\CartCode;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\GiftCard\GiftCardFactory getFactory()
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
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCode()
            ->addCandidate($quoteTransfer, $code);
    }

    /**
     * {@inheritDoc}
     * - Removes matching applied gift card and gift card payment from quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCode()
            ->removeCode($quoteTransfer, $code);
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
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function getOperationResponseMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCode()
            ->getOperationResponseMessage($quoteTransfer, $code);
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
    public function clearAllCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCode()
            ->clearAllCodes($quoteTransfer);
    }
}
