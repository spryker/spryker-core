<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface AvailabilityFacadeInterface
{

    /**
     * Specification:
     *  - Check if product is never out of stock
     *  - Check if product have stock in productStock table
     *  - Check if have placed orders where items have statemachine state flagged as reserved
     *
     * @api
     *
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity);

    /**
     * Specification:
     *  - Check if product have stock in productStock table
     *  - Check if have placed orders where items have statemachine state flagged as reserved
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku);

    /**
     * Specification:
     *  - Checkout PreCondition plugin call, check if all items in cart is sellable.
     *  - Write error message into CheckoutResponseTransfer
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
    );

    /**
     * Specification:
     *  - Calculate current item stock, take into account reserved items
     *  - Store new stock for concrete product
     *  - Store sum of all concrete product stocks for abstract product
     *  - Touch availability abstract collector
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku);

    /**
     *
     * Specification:
     *  - Reads product availability data from persistense, stock, reservation, availability.
     *  - Returns data for selected abstract product
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getProductAbstractAvailability($idProductAbstract, $idLocale);

    /**
     *
     * Specification:
     *  - Touches availability abstract collector for given abstract product
     *
     * @api
     *
     * @param int
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract);

}
