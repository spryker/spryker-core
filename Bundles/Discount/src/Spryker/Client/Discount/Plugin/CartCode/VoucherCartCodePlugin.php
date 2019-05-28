<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Discount\Plugin\CartCode;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Discount\DiscountFactory getFactory()
 */
class VoucherCartCodePlugin extends AbstractPlugin implements CartCodePluginInterface
{
    /**
     * {@inheritdoc}
     * - Sets voucher discount to the quote if the code hasn't been added already.
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
            ->createVoucherCartCode()
            ->addCandidate($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Removes matching applied and not applied voucher discount from quote.
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
            ->createVoucherCartCode()
            ->removeCode($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Returns voucher apply success message in case the given voucher code has been applied successfully.
     * - Returns voucher apply failed message in case the given voucher code hasn't been applied successfully.
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
            ->createVoucherCartCode()
            ->getOperationResponseMessage($quoteTransfer, $code);
    }

    /**
     * {@inheritdoc}
     * - Clears all applied and not applied voucher codes from the quote.
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
            ->createVoucherCartCode()
            ->clearAllCodes($quoteTransfer);
    }
}
