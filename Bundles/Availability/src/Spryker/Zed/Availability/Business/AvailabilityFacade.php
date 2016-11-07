<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityBusinessFactory getFactory()
 */
class AvailabilityFacade extends AbstractFacade implements AvailabilityFacadeInterface
{

    /**
     * Specification:
     *  - Checks if product is never out of stock
     *  - Checks if product have stock in productStock table
     *  - Checks if have placed orders where items have statemachine state flagged as reserved
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->isProductSellable($sku, $quantity);
    }

    /**
     * Specification:
     *  - Checks if product have stock in productStock table
     *  - Checks if have placed orders where items have statemachine state flagged as reserved
     *  - Returns integer value which is Product stock - reserved state machine items.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()
            ->createSellableModel()
            ->calculateStockForProduct($sku);
    }

    /**
     * Specification:
     *  - Checkout PreCondition plugin call, check if all items in cart is sellable.
     *  - Writes error message into CheckoutResponseTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkoutAvailabilityPreCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getFactory()
            ->createProductsAvailablePreCondition()
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     *
     * Specification:
     *  - Calculates current item stock, take into account reserved items
     *  - Stores new stock for concrete product
     *  - Stores sum of all concrete product stocks for abstract product
     *  - Touches availability abstract collector
     *
     * @api
     *
     * @param string $sku
     *
     * @throw ProductNotFoundException
     *
     * @return void
     */
    public function updateAvailability($sku)
    {
        $this->getFactory()
            ->createAvailabilityHandler()
            ->updateAvailability($sku);
    }

}
