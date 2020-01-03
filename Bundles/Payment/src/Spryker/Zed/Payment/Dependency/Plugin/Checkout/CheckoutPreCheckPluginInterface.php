<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Interface for the plugin stack defined as \Spryker\Zed\Payment\PaymentDependencyProvider::CHECKOUT_PRE_CHECK_PLUGINS
 */
interface CheckoutPreCheckPluginInterface extends CheckoutPluginInterface
{
    /**
     * Specification:
     * - Executes a pre-condition for checkout
     * - Returns `false` if a pre-condition is not passed
     * - Check could pass even if CheckoutResponse errors are filled – in that case execution will continue
     * - Deprecated: Notifies about failed condition by filling CheckoutResponse errors, when output is `null`
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);
}
