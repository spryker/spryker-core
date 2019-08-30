<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Plugin\Checkout;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Client\CheckoutExtension\Dependency\Plugin\CheckoutPreCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @deprecated
 *
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalClient getClient()
 */
class QuoteApprovalCheckoutPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{
    protected const MESSAGE_CART_REQUIRE_APPROVAL = 'quote_approval.cart.require_approval';

    /**
     * {@inheritdoc}
     * - Successful if customer does't have RequestQuoteApprovalPermissionPlugin permission assigned.
     * - Successful if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Successful if quote approval status is `approved`.
     * - Unsuccessful otherwise.
     * - Returns message if not successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isValid(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $canProceedCheckout = !$this->getClient()->isQuoteApprovalRequired($quoteTransfer);

        if ($canProceedCheckout === true) {
            return (new QuoteValidationResponseTransfer())->setIsSuccessful(true);
        }

        return (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue(static::MESSAGE_CART_REQUIRE_APPROVAL)
            );
    }
}
