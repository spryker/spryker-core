<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\CartCode;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePostAddPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Nopayment\Business\NopaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Nopayment\NopaymentConfig getConfig()
 */
class NopaymentCartCodePostAddPlugin extends AbstractPlugin implements CartCodePostAddPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `QuoteTransfer.totals.priceToPay` equals zero.
     * - Updates `QuoteTransfer.payment` to `PaymentTransfer` with no payment if condition is met.
     * - Returns `CartCodeRequestTransfer.isSuccessful` with `true` on success or `false` on error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function execute(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->getFacade()->updateCartCodeQuotePayment($cartCodeRequestTransfer);
    }
}
