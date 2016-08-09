<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOption\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
interface ProductOptionFacadeInterface
{

    /**
     * Specification:
     *  - Persist new product option group
     *  - Persist option values if provided
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer);

    /**
     * Specification:
     *  - Persist new product option value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return int
     */
    public function saveProductOptionValue(ProductOptionValueTransfer $productOptionValueTransfer);

    /**
     * Specification:
     *  - Attach abstract product to existing product group
     *
     * @api
     *
     * @param string $abstractSku
     * @param int $idProductOptionGroup
     *
     * @return bool|void
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup);

    /**
     * Specification:
     *  - Read product option from persistence
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValue($idProductOptionValue);

    /**
     *
     * Specification:
     *  - Get product option group from persistence
     *  - Get all related product option values
     *
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function getProductOptionGroupById($idProductOptionGroup);

    /**
     *
     * Specification:
     *  - Loops over all items and calculates gross amount for each items
     *  - Data is read from sales order persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemProductOptionGrossPrice(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over all items and calculates subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotalWithProductOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Persist product option sales data
     *  - Used by sales saver plugin
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSaleOrderProductOptions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     *  - Calculate tax rate for current quote
     *  - Set tax rate perecentage
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductOptionTaxRate(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Toggle option active/inactive
     *
     * @api
     *
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @return void
     */
    public function toggleOptionActive($idProductOptionGroup, $isActive);

}
