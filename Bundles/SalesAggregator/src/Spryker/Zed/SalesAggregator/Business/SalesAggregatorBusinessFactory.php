<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\DiscountTotalAmount;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ExpenseTax;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ExpenseTotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\GrandTotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\GrandTotalWithDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Item;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemProductOptionGrossPrice;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderExpensesWithDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderExpenseTaxWithDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionDiscountTotalAmount;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionOrderDiscounts;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Subtotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\SubtotalWithProductOptions;
use Spryker\Zed\SalesAggregator\Business\Model\OrderTotalsAggregator;
use Spryker\Zed\SalesAggregator\SalesAggregatorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesAggregator\SalesAggregatorConfig getConfig()
 */
class SalesAggregatorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderTotalsAggregatorInterface
     */
    public function createOrderTotalsAggregator()
    {
        return new OrderTotalsAggregator(
            $this->getOrderTotalAggregatorPlugins(),
            $this->getItemTotalAggregatorPlugins(),
            $this->getSalesQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createExpenseOrderTotalAggregator()
    {
        return new ExpenseTotal($this->getSalesQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createGrandTotalOrderTotalAggregator()
    {
        return new GrandTotal();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createItemOrderOrderAggregator()
    {
        return new Item();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createSubtotalOrderAggregator()
    {
        return new Subtotal();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createOrderItemTaxAmountAggregator()
    {
        return new ItemTax($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmount();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createOrderExpenseTaxAmountAggregator()
    {
        return new ExpenseTax($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createItemProductOptionGrossPriceAggregator()
    {
        return new ItemProductOptionGrossPrice($this->getProductOptionsQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface
     */
    public function createSubtotalWithProductOption()
    {
        return new SubtotalWithProductOptions();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\DiscountTotalAmount
     */
    public function createOrderDiscountTotalAmount()
    {
        return new DiscountTotalAmount();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemDiscounts
     */
    public function createItemTotalOrderAggregator()
    {
        return new ItemDiscounts($this->getDiscountQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderDiscounts
     */
    public function createSalesOrderTotalsAggregator()
    {
        return new OrderDiscounts();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\GrandTotalWithDiscounts
     */
    public function createSalesOrderGrandTotalAggregator()
    {
        return new GrandTotalWithDiscounts();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderExpenseTaxWithDiscounts
     */
    public function createOrderExpenseTaxWithDiscountsAggregator()
    {
        return new OrderExpenseTaxWithDiscounts(
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderExpensesWithDiscounts
     */
    public function createOrderExpenseWithDiscountsAggregator()
    {
        return new OrderExpensesWithDiscounts($this->getDiscountQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionDiscountTotalAmount
     */
    public function createDiscountTotalWithProductOptionsCalculator()
    {
        return new ProductOptionDiscountTotalAmount();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionOrderDiscounts
     */
    public function createProductOptionOrderDiscountAggregator()
    {
        return new ProductOptionOrderDiscounts();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ProductOptionDiscounts
     */
    public function createProductOptionDiscountCalculator()
    {
        return new ProductOptionDiscounts(
            $this->getDiscountQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts
     */
    public function createItemProductOptionsAndDiscountsTaxCalculator()
    {
        return new ItemProductOptionTaxWithDiscounts($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts
     */
    public function createOrderTotalWithDiscountsTaxCalculator()
    {
        return new OrderTaxAmountWithDiscounts();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getOrderTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::PLUGINS_ORDER_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected function getProductOptionsQueryContainer()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::QUERY_CONTAINER_PRODUCT_OPTIONS);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getItemTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::PLUGINS_ITEM_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function getDiscountQueryContainer()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::QUERY_CONTAINER_DISCOUNTS);
    }

}
