<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ExpenseTax;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ExpenseTotal;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\GrandTotal;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Item;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\OrderTaxAmount;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Subtotal;
use Spryker\Zed\Sales\Business\Model\OrderReferenceGenerator;
use Spryker\Zed\Sales\Business\Model\OrderTotalsAggregator;
use Spryker\Zed\Sales\Business\Model\Split\Validation\Validator;
use Spryker\Zed\Sales\Business\Model\Split\Calculator;
use Spryker\Zed\Sales\Business\Model\Split\OrderItem;
use Spryker\Zed\Sales\Business\Model\OrderManager;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Model\CommentManager;
use Spryker\Zed\Sales\Business\Model\OrderDetailsManager;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainer getQueryContainer()
 */
class SalesBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderManager
     */
    public function createOrderManager()
    {
        return new OrderManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->createReferenceGenerator(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CommentManager
     */
    public function createCommentsManager()
    {
        $userFacade = $this->getProvidedDependency(SalesDependencyProvider::FACADE_USER);

        return new CommentManager(
            $this->getQueryContainer(),
            $userFacade->getCurrentUser()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderDetailsManager
     */
    public function createOrderDetailsManager()
    {
        return new OrderDetailsManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS),
            $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_PAYMENT_LOGS)
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\ItemInterface
     */
    public function createOrderItemSplitter()
    {
        return new OrderItem(
            $this->createSplitValidator(),
            $this->getQueryContainer(),
            $this->createCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\Validation\ValidatorInterface
     */
    protected function createSplitValidator()
    {
        $validator = new Validator();

        return $validator;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface
     */
    public function createReferenceGenerator()
    {
        $sequenceNumberSettings = $this->getConfig()->getOrderReferenceDefaults();

        return new OrderReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $sequenceNumberSettings
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Split\Calculator
     */
    protected function createCalculator()
    {
        return new Calculator();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderTotalsAggregator
     */
    public function createOrderTotalsAggregator()
    {
        return new OrderTotalsAggregator(
            $this->getOrderTotalAggregatorPlugins(),
            $this->getItemTotalAggregatorPlugins(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getOrderTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getItemTotalAggregatorPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ITEM_AMOUNT_AGGREGATION);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ExpenseTotal
     */
    public function createExpenseOrderTotalAggregator()
    {
        return new ExpenseTotal($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\GrandTotal
     */
    public function createGrandTotalOrderTotalAggregator()
    {
        return new GrandTotal();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Item
     */
    public function createItemOrderOrderAggregator()
    {
        return new Item();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\Subtotal
     */
    public function createSubtotalOrderAggregator()
    {
        return new Subtotal();
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getFacadeOms()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToRefundInterface
     */
    public function getFacadeRefund()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ItemTax
     */
    public function createOrderItemTaxAmountAggregator()
    {
        return new ItemTax($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    public function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmount($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    public function createOrderExpenseTaxAmountAggregator()
    {
        return new ExpenseTax($this->getTaxFacade());
    }

}
