<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Plugin\Quote;

use Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CheckoutExtension\Dependency\Plugin\QuoteProceedCheckoutCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalClient getClient()
 */
class QuoteApprovalProceedCheckoutCheckPlugin extends AbstractPlugin implements QuoteProceedCheckoutCheckPluginInterface
{
    protected const MESSAGE_CART_REQUIRE_APPROVAL = 'quote_approval.cart.require_approval';

    /**
     * {@inheritdoc}
     * - Successful if customer does't have PlaceOrderPermissionPlugin permission assigned.
     * - Successful if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Successful if quote approval status is `approved`.
     * - Unsuccessful otherwise.
     * - Returns message if not successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer
     */
    public function can(QuoteTransfer $quoteTransfer): CanProceedCheckoutResponseTransfer
    {
        $canProceedCheckout = !$this->getClient()->isQuoteRequireApproval($quoteTransfer);

        $canProceedCheckoutResponseTransfer = new CanProceedCheckoutResponseTransfer();
        $canProceedCheckoutResponseTransfer->setIsSuccessful($canProceedCheckout);

        if ($canProceedCheckout) {
            return $canProceedCheckoutResponseTransfer;
        }

        $canProceedCheckoutResponseTransfer->addMessage(
            (new MessageTransfer())->setValue(static::MESSAGE_CART_REQUIRE_APPROVAL)
        );

        return $canProceedCheckoutResponseTransfer;
    }
}
