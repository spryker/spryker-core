<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the quote is ready to checkout.
     * - Returns response with boolean isSuccess and an array of errors.
     * - Returns isSuccess false if customer does't have RequestQuoteApprovalPermissionPlugin permission assigned.
     * - Returns isSuccess false if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Returns isSuccess false if quote approval status is not `approved`.
     * - Returns isSuccess true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $checkoutResponseTransfer = $this->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer->getIsSuccess();
    }
}
