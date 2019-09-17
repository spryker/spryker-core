<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    protected const MESSAGE_CART_REQUIRE_APPROVAL = 'quote_approval.cart.require_approval';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $canProceedCheckout = !$this->getFacade()->isQuoteApprovalRequired($quoteTransfer);
        $checkoutResponseTransfer->setIsSuccess($canProceedCheckout);

        if ($canProceedCheckout === true) {
            $checkoutErrorTransfers = new ArrayObject();
            $checkoutErrorTransfers->append(
                (new QuoteErrorTransfer())->setMessage(static::MESSAGE_CART_REQUIRE_APPROVAL)
            );

            $checkoutResponseTransfer->setErrors($checkoutErrorTransfers);
        }

        return $canProceedCheckout;
    }
}
