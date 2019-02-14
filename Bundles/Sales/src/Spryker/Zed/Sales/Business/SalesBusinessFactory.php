<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\Address\OrderAddressWriter;
use Spryker\Zed\Sales\Business\Address\OrderAddressWriterInterface;
use Spryker\Zed\Sales\Business\Expense\ExpenseWriter;
use Spryker\Zed\Sales\Business\Expense\ExpenseWriterInterface;
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
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverInterface;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutor;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemGrouper;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemGrouperInterface;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapper;
use Spryker\Zed\Sales\Business\Order\OrderHydrator as OrderHydratorWithMultiShippingAddress;
use Spryker\Zed\Sales\Business\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Business\Order\OrderHydratorOrderDataBCForMultiShipmentAdapter;
use Spryker\Zed\Sales\Business\Order\OrderHydratorOrderDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\Sales\Business\Order\OrderReader as OrderReaderWithMultiShippingAddress;
use Spryker\Zed\Sales\Business\Order\OrderReaderInterface;
use Spryker\Zed\Sales\Business\Order\SalesOrderSaver as SalesOrderSaverMultipleShippingAddress;
use Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface as SalesOrderSaverMultipleShippingAddressInterface;
use Spryker\Zed\Sales\Business\Order\SalesOrderSaverQuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\Sales\Business\Order\SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolver;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderReaderStrategyResolver;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderReaderStrategyResolverInterface;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderSaverStrategyResolver;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderSaverStrategyResolverInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface;
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
            $this->createOrderHydratorStrategyResolver()->resolve(),
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
            $this->createOrderHydratorStrategyResolver()->resolve(),
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
     * @deprecated Use createSalesOrderSaverMultipleShippingAddress() instead.
     *
     * @return \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverInterface
     */
    public function createSalesOrderSaver(): SalesOrderSaverInterface
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
     * @return \Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface
     */
    public function createSalesOrderSaverMultipleShippingAddress(): SalesOrderSaverMultipleShippingAddressInterface
    {
        return new SalesOrderSaverMultipleShippingAddress(
            $this->getCountryFacade(),
            $this->getOmsFacade(),
            $this->createReferenceGenerator(),
            $this->getConfig(),
            $this->getLocaleQueryContainer(),
            $this->getStore(),
            $this->getOrderExpanderPreSavePlugins(),
            $this->createSalesOrderSaverPluginExecutor(),
            $this->createOrderItemMapper(),
            $this->createSalesOrderSaverQuoteDataBCForMultiShipmentAdapter()
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
            $this->createOrderHydratorStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Order\OrderReaderInterface
     */
    public function createOrderReaderWithMultiShippingAddress(): OrderReaderInterface
    {
        return new OrderReaderWithMultiShippingAddress(
            $this->getQueryContainer(),
            $this->createOrderHydratorStrategyResolver()->resolve(),
            $this->createOrderHydratorOrderDataBCForMultiShipmentAdapter()
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReader
     */
    public function createOrderRepositoryReader()
    {
        return new OrderRepositoryReader(
            $this->createOrderHydratorStrategyResolver()->resolve(),
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
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorInterface
     */
    public function createOrderHydratorWithMultiShippingAddress(): OrderHydratorInterface
    {
        return new OrderHydratorWithMultiShippingAddress(
            $this->getQueryContainer(),
            $this->getOmsFacade(),
            $this->createOrderHydratorOrderDataBCForMultiShipmentAdapter(),
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
     * @return \Spryker\Zed\Sales\Business\Address\OrderAddressWriterInterface
     */
    public function createOrderAddressWriter(): OrderAddressWriterInterface
    {
        return new OrderAddressWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getCountryFacade()
        );
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

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\Sales\Business\Order\SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createSalesOrderSaverQuoteDataBCForMultiShipmentAdapter(): SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface
    {
        return new SalesOrderSaverQuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorOrderDataBCForMultiShipmentAdapterInterface
     */
    protected function createOrderHydratorOrderDataBCForMultiShipmentAdapter(): OrderHydratorOrderDataBCForMultiShipmentAdapterInterface
    {
        return new OrderHydratorOrderDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createSalesOrderSaverMultipleShippingAddress() instead.
     *
     * @return \Spryker\Zed\Sales\Business\StrategyResolver\OrderSaverStrategyResolverInterface
     */
    public function createOrderSaverStrategyResolver(): OrderSaverStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addStrategySalesOrderSaverWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addStrategySalesOrderSaverWithMultipleShippingAddress($strategyContainer);

        return new OrderSaverStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategySalesOrderSaverWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderSaverStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createSalesOrderSaver();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategySalesOrderSaverWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderSaverStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createSalesOrderSaverMultipleShippingAddress();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createOrderHydratorWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface
     */
    public function createOrderHydratorStrategyResolver(): OrderHydratorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addStrategyOrderHydratorWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addStrategyOrderHydratorWithMultipleShippingAddress($strategyContainer);

        return new OrderHydratorStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategyOrderHydratorWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderHydratorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createOrderHydrator();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategyOrderHydratorWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderHydratorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createOrderHydratorWithMultiShippingAddress();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createOrderReaderWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Sales\Business\StrategyResolver\OrderReaderStrategyResolverInterface
     */
    public function createOrderReaderStrategyResolver(): OrderReaderStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addStrategyOrderReaderWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addStrategyOrderReaderWithMultipleShippingAddress($strategyContainer);

        return new OrderReaderStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategyOrderReaderWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderReaderStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createOrderReader();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addStrategyOrderReaderWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderReaderStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createOrderReaderWithMultiShippingAddress();
        };

        return $strategyContainer;
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface
     */
    protected function getShipmentService(): SalesToShipmentServiceInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemGrouperInterface
     */
    public function createSalesOrderItemGrouper(): SalesOrderItemGrouperInterface
    {
        return new SalesOrderItemGrouper($this->getShipmentService());
    }
}
