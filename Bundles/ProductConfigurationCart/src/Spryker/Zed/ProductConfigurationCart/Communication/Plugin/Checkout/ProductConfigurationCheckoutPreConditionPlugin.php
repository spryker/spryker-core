<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig getConfig()
 */
class ProductConfigurationCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if all product configuration items in the quote have complete configuration, false otherwise.
     * - If the quote item configuration is not available, an error code and message are added to the response.
     * - If the quote item configuration is incomplete, an error code and message are added to the response.
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
        return $this->getFacade()->isQuoteProductConfigurationValid($quoteTransfer, $checkoutResponseTransfer);
    }
}
