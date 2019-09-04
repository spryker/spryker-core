<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Address\OrderAddressWriter;
use Spryker\Zed\Sales\Business\Address\OrderAddressWriterInterface;
use Spryker\Zed\Sales\Business\Expander\SalesAddressExpander;
use Spryker\Zed\Sales\Business\Expander\SalesAddressExpanderInterface;
use Spryker\Zed\Sales\Business\Expense\ExpenseUpdater;
use Spryker\Zed\Sales\Business\Expense\ExpenseUpdaterInterface;
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
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapper as ModelSalesOrderItemMapper;
use Spryker\Zed\Sales\Business\Order\OrderHydrator as OrderHydratorWithMultiShippingAddress;
use Spryker\Zed\Sales\Business\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Business\Order\OrderReader as OrderReaderWithMultiShippingAddress;
use Spryker\Zed\Sales\Business\Order\OrderReaderInterface;
use Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouper;
use Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouperInterface;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolver;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;
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
            $this->createOrderHydratorStrategyResolver(),
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
            $this->createOrderHydratorStrategyResolver(),
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
            $this->createSalesOrderItemMapper(),
            $this->getOrderPostSavePlugins()
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
            $this->createSalesOrderItemMapper(),
            $this->getOrderPostSavePlugins()
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
     * @deprecated Use createOrderReaderWithMultiShippingAddress() instead.
     *
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
     * @return \Spryker\Zed\Sales\Business\Order\OrderReaderInterface
     */
    public function createOrderReaderWithMultiShippingAddress(): OrderReaderInterface
    {
        return new OrderReaderWithMultiShippingAddress(
            $this->getQueryContainer(),
            $this->createOrderHydratorWithMultiShippingAddress()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReader
     */
    public function createOrderRepositoryReader()
    {
        return new OrderRepositoryReader(
            $this->createOrderHydratorStrategyResolver(),
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
     * @deprecated Use createOrderHydratorWithMultiShippingAddress() instead.
     *
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
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    public function createOrderHydratorWithMultiShippingAddress(): OrderHydratorInterface
    {
        return new OrderHydratorWithMultiShippingAddress(
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
     * @deprecated Use createOrderAddressWriter() instead.
     *
     * @return \Spryker\Zed\Sales\Business\Model\Address\OrderAddressUpdaterInterface
     */
    public function createOrderAddressUpdater()
    {
        return new OrderAddressUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Address\OrderAddressWriterInterface
     */
    public function createOrderAddressWriter(): OrderAddressWriterInterface
    {
        return new OrderAddressWriter($this->getEntityManager(), $this->getCountryFacade());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Expander\SalesAddressExpanderInterface
     */
    public function createSalesAddressExpander(): SalesAddressExpanderInterface
    {
        return new SalesAddressExpander($this->getCustomerFacade(), $this->getRepository());
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
     * @return \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface
     */
    public function createSalesOrderSaverPluginExecutor()
    {
        return new SalesOrderSaverPluginExecutor(
            $this->getOrderItemExpanderPreSavePlugins()
        );
    }

    /**
     * @deprecated Use createSalesOrderItemMapper() instead.
     *
     * @return \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface
     */
    public function createOrderItemMapper()
    {
        return new ModelSalesOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Expense\ExpenseWriterInterface
     */
    public function createExpenseWriter(): ExpenseWriterInterface
    {
        return new ExpenseWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Expense\ExpenseUpdaterInterface
     */
    public function createExpenseUpdater(): ExpenseUpdaterInterface
    {
        return new ExpenseUpdater($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouperInterface
     */
    public function createSalesOrderItemGrouper(): SalesOrderItemGrouperInterface
    {
        return new SalesOrderItemGrouper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    public function createSalesOrderItemMapper(): SalesOrderItemMapperInterface
    {
        return new SalesOrderItemMapper();
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
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[]
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
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface[]
     */
    public function getOrderItemExpanderPreSavePlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\ItemTransformerStrategyPluginInterface[]
     */
    public function getItemTransformerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesDependencyProvider::ITEM_TRANSFORMER_STRATEGY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface[]
     */
    public function getOrderPostSavePlugins(): array
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createOrderHydratorWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface
     */
    public function createOrderHydratorStrategyResolver(): OrderHydratorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[OrderHydratorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createOrderHydrator();
        };

        $strategyContainer[OrderHydratorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createOrderHydratorWithMultiShippingAddress();
        };

        return new OrderHydratorStrategyResolver($strategyContainer);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface
     */
    public function getCustomerFacade(): SalesToCustomerInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_CUSTOMER);
    }
}
