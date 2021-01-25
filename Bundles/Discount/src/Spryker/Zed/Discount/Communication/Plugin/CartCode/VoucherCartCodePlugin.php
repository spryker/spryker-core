<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\CartCode;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 */
class VoucherCartCodePlugin extends AbstractPlugin implements CartCodePluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets voucher discount to the quote if the code hasn't been added already.
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
     * - Removes matching applied and not applied voucher discount from quote.
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
     * - Clears all (both applied and unapplied) voucher codes from the Quote.
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
     * - Returns a MessageTransfer with a Successfully Applied Voucher message when the voucher was applied successfully.
     * - Returns a MessageTransfer with a Failed to Apply Voucher message when the voucher was applied unsuccessfully.
     * - Returns an empty failed message when the code is not applicable.
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
