<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesAggregator\Business;

use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ExpenseTax;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ExpenseTotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\GrandTotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Item;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Subtotal;
use Spryker\Zed\SalesAggregator\Business\Model\OrderTotalsAggregator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesAggregator\SalesAggregatorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesAggregator\SalesAggregatorConfig getConfig()
 */
class SalesAggregatorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderTotalsAggregator
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
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getOrderTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::PLUGINS_ORDER_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getItemTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::PLUGINS_ITEM_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ExpenseTotal
     */
    public function createExpenseOrderTotalAggregator()
    {
        return new ExpenseTotal($this->getSalesQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\GrandTotal
     */
    public function createGrandTotalOrderTotalAggregator()
    {
        return new GrandTotal();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Item
     */
    public function createItemOrderOrderAggregator()
    {
        return new Item();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\Subtotal
     */
    public function createSubtotalOrderAggregator()
    {
        return new Subtotal();
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\ItemTax
     */
    public function createOrderItemTaxAmountAggregator()
    {
        return new ItemTax($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    public function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmount($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    public function createOrderExpenseTaxAmountAggregator()
    {
        return new ExpenseTax($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::SALES_QUERY_CONTAINER);
    }

}
