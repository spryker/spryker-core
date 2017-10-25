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
     *  - Persist new product option group, update existing group if idOptionGroup is set
     *  - Persist option values if provided
     *  - Adds abstract products if provided in productsToBeAssigned array of primary keys
     *  - Removes abstract products if provided in productsToBeDeAssigned array of primary keys
     *  - Removes product option values if provided in productOptionValuesToBeRemoved array of primary keys
     *  - Persists value and group name translations, add to glossary
     *  - Returns id of option group
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer);

    /**
     * Specification:
     *  - Persist new product option value, updates existing value if idOptionValue is set
     *  - Returns id of option value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return int
     */
    public function saveProductOptionValue(ProductOptionValueTransfer $productOptionValueTransfer);

    /**
     * Specification:
     *  - Attaches abstract product to existing product group
     *  - Returns true if product successfully added
     *
     * @api
     *
     * @param string $abstractSku
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException
     *
     * @return bool
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup);

    /**
     * Specification:
     *  - Reads product option from persistence
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValueById($idProductOptionValue);

    /**
     * Specification:
     *  - Gets product option group from persistence
     *  - Gets all related product option values
     *
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function getProductOptionGroupById($idProductOptionGroup);

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
     *  - Toggle option active/inactive, option wont be diplayed in Yves when disabled. Collectors have to run first.
     *
     * @api
     *
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @return bool
     */
    public function toggleOptionActive($idProductOptionGroup, $isActive);

    /**
     * Specification:
     *  - Hydrate product options for given order transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderProductOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Sorts sales order items within the provided transfer object.
     * - 3 level sorting is applied:
     *   - items without options come first,
     *   - items are ordered by SKU,
     *   - items are ordered by ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function sortSalesOrderItemsByOptions(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Hydrates existing production option group ids within the provided OrderTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductOptionGroupIds(OrderTransfer $orderTransfer);
}
