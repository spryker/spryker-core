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
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
class ProductOptionFacade extends AbstractFacade implements ProductOptionFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use saveOrderProductOptions() instead
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderProductOptions(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFactory()
            ->createPlaceOrderProductOptionOrderSaver()
            ->saveOrderProductOptions($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function sortSalesOrderItemsByOptions(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductOptionItemSorter()
            ->sortItemsBySkuAndOptions($orderTransfer);
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
    public function hydrateProductOptionGroupIds(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductOptionGroupIdHydrator()
            ->hydrateProductOptionGroupIds($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getProductOptionValueStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer)
    {
        return $this->getFactory()
            ->createProductOptionValuePriceReader()
            ->getStorePrices($storePricesRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getAllProductOptionValuePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer)
    {
        return $this->getFactory()
            ->createProductOptionValuePriceReader()
            ->getAllPrices($storePricesRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getProductOptionCollectionByProductOptionCriteria(ProductOptionCriteriaTransfer $productOptionCriteriaTransfer): ProductOptionCollectionTransfer
    {
        return $this->getFactory()
            ->createProductOptionValueReader()
            ->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);
    }
}
