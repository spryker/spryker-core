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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
class ProductOptionFacade extends AbstractFacade implements ProductOptionFacadeInterface
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
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        return $this->getFactory()
           ->createProductOptionGroupSaver()
           ->saveProductOptionGroup($productOptionGroupTransfer);
    }

    /**
     * Specification:
     *  - Persist new product option value, updates existing value if idOptionValue is set
     *  - Returns id of option value
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return int
     */
    public function saveProductOptionValue(ProductOptionValueTransfer $productOptionValueTransfer)
    {
        return $this->getFactory()
            ->createProductOptionValueSaver()
            ->saveProductOptionValue($productOptionValueTransfer);
    }

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
     * @return bool
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup)
    {
        return $this->getFactory()
            ->createAbstractProductOptionSaver()
            ->addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup);
    }

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
    public function getProductOptionValueById($idProductOptionValue)
    {
        return $this->getFactory()
            ->createProductOptionValueReader()
            ->getProductOption($idProductOptionValue);
    }

    /**
     *
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
    public function getProductOptionGroupById($idProductOptionGroup)
    {
        return $this->getFactory()
            ->createProductOptionGroupReader()
            ->getProductOptionGroupById($idProductOptionGroup);
    }

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
    public function aggregateOrderItemProductOptionGrossPrice(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createItemProductOptionGrossPriceAggregator()
            ->aggregate($orderTransfer);
    }

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
    public function aggregateOrderSubtotalWithProductOptions(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSubtotalWithProductOption()
            ->aggregate($orderTransfer);
    }

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
    public function saveSaleOrderProductOptions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()
            ->createProductOptionOrderSaver()
            ->save($quoteTransfer, $checkoutResponse);
    }

    /**
     * Specification:
     *  - Calculate tax rate for current quote
     *  - Set tax rate percentage
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductOptionTaxRate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createProductOptionTaxRateCalculator()
            ->recalculate($quoteTransfer);
    }

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
    public function toggleOptionActive($idProductOptionGroup, $isActive)
    {
        return $this->getFactory()
            ->createProductOptionGroupSaver()
            ->toggleOptionActive($idProductOptionGroup, $isActive);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderProductOptions(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductOptionOrderHydrate()
            ->hydrate($orderTransfer);
    }

}
