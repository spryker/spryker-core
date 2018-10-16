<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Expense\ExpenseWriter;
use Spryker\Zed\Sales\Business\Expense\ExpenseWriterInterface;
use Spryker\Zed\Sales\Business\Model\Address\OrderAddressUpdater;
use Spryker\Zed\Sales\Business\Model\Comment\OrderCommentReader;
use Spryker\Zed\Sales\Business\Model\Comment\OrderCommentSaver;
use Spryker\Zed\Sales\Business\Model\Customer\CustomerOrderOverviewInterface;
use Spryker\Zed\Sales\Business\Model\Customer\CustomerOrderReader;
use Spryker\Zed\Sales\Business\Model\Customer\PaginatedCustomerOrderOverview;
use Spryker\Zed\Sales\Business\Model\Customer\PaginatedCustomerOrderReader;
use Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydrator;
use Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface;
use Spryker\Zed\Sales\Business\Model\Order\OrderExpander;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydrator;
use Spryker\Zed\Sales\Business\Model\Order\OrderReader;
use Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGenerator;
use Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReader;
use Spryker\Zed\Sales\Business\Model\Order\OrderSaver;
use Spryker\Zed\Sales\Business\Model\Order\OrderUpdater;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaver;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutor;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapper;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface getEntityManager()
 */
class SalesBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Sales\Business\Model\Customer\CustomerOrderReaderInterface
     */
    public function createCustomerOrderReader()
    {
        return new CustomerOrderReader(
            $this->getQueryContainer(),
            $this->createOrderHydrator(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Customer\CustomerOrderReaderInterface
     */
    public function createPaginatedCustomerOrderReader()
    {
        return new PaginatedCustomerOrderReader(
            $this->getQueryContainer(),
            $this->createOrderHydrator(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Customer\CustomerOrderOverviewInterface
     */
    public function createPaginatedCustomerOrderOverview(): CustomerOrderOverviewInterface
    {
        return new PaginatedCustomerOrderOverview(
            $this->getQueryContainer(),
            $this->createCustomerOrderOverviewHydrator(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface
     */
    public function createCustomerOrderOverviewHydrator(): CustomerOrderOverviewHydratorInterface
    {
        return new CustomerOrderOverviewHydrator();
    }

    /**
     * @deprecated Use createSalesOrderSaver() instead.
     *
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderSaverInterface
     */
    public function createOrderSaver()
    {
        return new OrderSaver(
            $this->getCountryFacade(),
            $this->getOmsFacade(),
            $this->createReferenceGenerator(),
            $this->getConfig(),
            $this->getLocaleQueryContainer(),
            $this->getStore(),
            $this->getOrderExpanderPreSavePlugins(),
            $this->createSalesOrderSaverPluginExecutor(),
            $this->createOrderItemMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverInterface
     */
    public function createSalesOrderSaver()
    {
        return new SalesOrderSaver(
            $this->getCountryFacade(),
            $this->getOmsFacade(),
            $this->createReferenceGenerator(),
            $this->getConfig(),
            $this->getLocaleQueryContainer(),
            $this->getStore(),
            $this->getOrderExpanderPreSavePlugins(),
            $this->createSalesOrderSaverPluginExecutor(),
            $this->createOrderItemMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderUpdaterInterface
     */
    public function createOrderUpdater()
    {
        return new OrderUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderReaderInterface
     */
    public function createOrderReader()
    {
        return new OrderReader(
            $this->getQueryContainer(),
            $this->createOrderHydrator()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReader
     */
    public function createOrderRepositoryReader()
    {
        return new OrderRepositoryReader(
            $this->createOrderHydrator(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Comment\OrderCommentReaderInterface
     */
    public function createOrderCommentReader()
    {
        return new OrderCommentReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Comment\OrderCommentSaverInterface
     */
    public function createOrderCommentSaver()
    {
        return new OrderCommentSaver($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    public function createOrderHydrator()
    {
        return new OrderHydrator(
            $this->getQueryContainer(),
            $this->getOmsFacade(),
            $this->getHydrateOrderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface
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
     * @return \Spryker\Zed\Sales\Business\Model\Address\OrderAddressUpdaterInterface
     */
    public function createOrderAddressUpdater()
    {
        return new OrderAddressUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderExpanderInterface
     */
    public function createOrderExpander()
    {
        return new OrderExpander(
            $this->getCalculationFacade(),
            $this->createOrderItemTransformer(),
            $this->getItemTransformerStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface
     */
    public function createOrderItemTransformer(): OrderItemTransformerInterface
    {
        return new OrderItemTransformer();
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface
     */
    protected function getCalculationFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    public function getLocaleQueryContainer()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::QUERY_CONTAINER_LOCALE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface[]
     */
    public function getHydrateOrderPlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::HYDRATE_ORDER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface[]
     */
    public function getOrderExpanderPreSavePlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::ORDER_EXPANDER_PRE_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface
     */
    public function createSalesOrderSaverPluginExecutor()
    {
        return new SalesOrderSaverPluginExecutor(
            $this->getOrderItemExpanderPreSavePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface[]
     */
    public function getOrderItemExpanderPreSavePlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface
     */
    public function createOrderItemMapper()
    {
        return new SalesOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\ItemTransformerStrategyPluginInterface[]
     */
    public function getItemTransformerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesDependencyProvider::ITEM_TRANSFORMER_STRATEGY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Expense\ExpenseWriterInterface
     */
    public function createExpenseWriter(): ExpenseWriterInterface
    {
        return new ExpenseWriter(
            $this->getEntityManager()
        );
    }
}
