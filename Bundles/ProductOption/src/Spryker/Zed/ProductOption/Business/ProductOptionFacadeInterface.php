<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
interface ProductOptionFacadeInterface
{
    /**
     * Specification:
     *  - Persist new product option group, update existing group if idOptionGroup is set.
     *  - Persist option values if provided.
     *  - Adds abstract products if provided in productsToBeAssigned array of primary keys.
     *  - Removes abstract products if provided in productsToBeDeAssigned array of primary keys.
     *  - Executes ProductOptionValuesPreRemovePluginInterface plugins before removing product option values.
     *  - Removes product option values if provided in productOptionValuesToBeRemoved array of primary keys.
     *  - Persists value and group name translations, add to glossary.
     *  - Persists multi-currency value prices.
     *  - Returns id of option group.
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
     * - Persist new product option value, updates existing value if idOptionValue is set.
     * - Persists multi-currency value prices.
     * - Returns id of option value.
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
     * - Reads product option from persistence.
     * - Net and gross unit prices are calculated using current store, and current currency.
     * - Uses default store (fkStore = NULL) prices when the option has no currency definition for the current store.
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
     * - Retrieves all product option group related production values from persistence.
     * - Populates all multi-currency prices for each product option value.
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
     *  - Persist product option sales data
     *  - Used by sales saver plugin
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderProductOptions(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     *  - Calculate tax rates for current quote level (BC) or item level shipping addresses.
     *  - Set tax rate percentages for item product options.
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
     * @deprecated Not used anymore.
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

    /**
     * Specification:
     * - Selects prices for the current store.
     * - The returned price map contains the net and gross amounts per currency.
     * - Uses "default store" (fkStore=NULL) currency prices when a store does not specify the prices in a currency.
     * - "default store" price is used for either net and gross price when it is null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getProductOptionValueStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);

    /**
     * Specification:
     * - Selects prices for all stores
     * - The returned price map contains the net and gross amounts per currency.
     * - Uses "default store" (fkStore=NULL) currency prices when a store does not specify the prices in a currency.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getAllProductOptionValuePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);

    /**
     * Specification:
     * - Retrieves product options by provided product option IDs.
     * - Filters by product options group active flag using ProductOptionCriteriaTransfer::ProductOptionGroupIsActive.
     * - Filters by product options group assignment to products using ProductOptionCriteriaTransfer::productConcreteSku.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getProductOptionCollectionByProductOptionCriteria(ProductOptionCriteriaTransfer $productOptionCriteriaTransfer): ProductOptionCollectionTransfer;

    /**
     * Specification:
     * - Finds product option by product option value id.
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer|null
     */
    public function findProductOptionByIdProductOptionValue(int $idProductOptionValue): ?ProductOptionTransfer;

    /**
     * Specification:
     * - Checks if product option value exists.
     *
     * @api
     *
     * @deprecated Use checkProductOptionGroupExistenceByProductOptionValueId() instead
     *
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    public function checkProductOptionValueExistence(int $idProductOptionValue): bool;

    /**
     * Specification:
     * - Checks if product option group exists using product option value id.
     *
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    public function checkProductOptionGroupExistenceByProductOptionValueId(int $idProductOptionValue): bool;

    /**
     * Specification:
     * - Retrieves product option group name and status for all abstract products by provided IDs.
     * - Returns ProductAbstractOptionGroupStatusTransfer[] array with 'idProductAbstract', 'isActive' and 'productOptionGroupName' values.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer[]
     */
    public function getProductAbstractOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array;
}
