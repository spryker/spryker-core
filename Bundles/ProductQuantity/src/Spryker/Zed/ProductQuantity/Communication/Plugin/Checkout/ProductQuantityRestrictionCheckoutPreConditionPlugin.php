<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 * @method \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductQuantity\Communication\ProductQuantityCommunicationFactory getFactory()
 */
class ProductQuantityRestrictionCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.items.sku` to be set.
     * - Validates if quote items fulfill all quantity restriction rules during checkout.
     * - Adds all found errors to `CheckoutResponseTransfer.errors`.
     * - Returns `true` if no errors were found, `false` otherwise.
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
        return $this->getFacade()->isValidItemQuantitiesOnCheckout(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );
    }
}
