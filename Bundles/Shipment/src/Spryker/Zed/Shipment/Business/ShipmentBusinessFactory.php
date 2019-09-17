<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Shipment\Business\Calculator\ShipmentTaxRateCalculator as ShipmentTaxRateCalculatorWithItemShipmentTaxRate;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaver as CheckoutShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Event\ShipmentEventGrouper;
use Spryker\Zed\Shipment\Business\Event\ShipmentEventGrouperInterface;
use Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpander;
use Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpanderInterface;
use Spryker\Zed\Shipment\Business\Mail\ShipmentOrderMailExpander;
use Spryker\Zed\Shipment\Business\Mail\ShipmentOrderMailExpanderInterface;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapper;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\MethodPrice;
use Spryker\Zed\Shipment\Business\Model\ShipmentCarrierReader;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderHydrate;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformer;
use Spryker\Zed\Shipment\Business\OrderItem\ShipmentSalesOrderItemReader;
use Spryker\Zed\Shipment\Business\OrderItem\ShipmentSalesOrderItemReaderInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizer;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentOrderHydrate as MultipleShipmentOrderHydrate;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentOrderHydrateInterface;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentReader;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentReaderInterface;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentSaver;
use Spryker\Zed\Shipment\Business\Shipment\ShipmentSaverInterface;
use Spryker\Zed\Shipment\Business\ShipmentExpense\MultiShipmentExpenseFilter;
use Spryker\Zed\Shipment\Business\ShipmentExpense\MultiShipmentExpenseFilterInterface;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCollectionRemover;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCollectionRemoverInterface;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreator;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreatorInterface;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseFilter;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseFilterInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcher;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupCreator;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupCreatorInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpander;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityChecker;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReader;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReader;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReader;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodCreator;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodCreatorInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDeleter;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDeleterInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdater;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdaterInterface;
use Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolver;
use Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolverInterface;
use Spryker\Zed\Shipment\Business\StrategyResolver\ShipmentExpenseFilterStrategyResolver;
use Spryker\Zed\Shipment\Business\StrategyResolver\ShipmentExpenseFilterStrategyResolverInterface;
use Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolver;
use Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class ShipmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Shipment\Business\Model\CarrierInterface
     */
    public function createCarrier()
    {
        return new Carrier();
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentCarrierReaderInterface
     */
    public function createShipmentCarrierReader()
    {
        return new ShipmentCarrierReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @deprecated Use createShipmentMethod() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\MethodInterface
     */
    public function createMethod()
    {
        return new Method(
            $this->getQueryContainer(),
            $this->createMethodPrice(),
            $this->createShipmentMethodTransformer(),
            $this->getCurrencyFacade(),
            $this->getStoreFacade(),
            $this->getPlugins(),
            $this->getMethodFilterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodCreatorInterface
     */
    public function createShipmentMethodCreator(): ShipmentMethodCreatorInterface
    {
        return new ShipmentMethodCreator(
            $this->getEntityManager(),
            $this->createMethodPrice()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdaterInterface
     */
    public function createShipmentMethodUpdater(): ShipmentMethodUpdaterInterface
    {
        return new ShipmentMethodUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createMethodPrice()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDeleterInterface
     */
    public function createShipmentMethodDeleter(): ShipmentMethodDeleterInterface
    {
        return new ShipmentMethodDeleter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    public function createMethodReader(): MethodReaderInterface
    {
        return new MethodReader(
            $this->getShipmentService(),
            $this->getMethodFilterPlugins(),
            $this->getRepository(),
            $this->createShipmentMethodAvailabilityChecker(),
            $this->createShipmentMethodPriceReader(),
            $this->createShipmentMethodDeliveryTimeReader()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface
     */
    public function createShipmentMethodTransformer()
    {
        return new ShipmentMethodTransformer(
            $this->getCurrencyFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseFilterInterface
     */
    public function createShipmentExpenseFilter(): ShipmentExpenseFilterInterface
    {
        return new ShipmentExpenseFilter();
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentExpense\MultiShipmentExpenseFilterInterface
     */
    public function createMultiShipmentExpenseFilter(): MultiShipmentExpenseFilterInterface
    {
        return new MultiShipmentExpenseFilter(
            $this->getShipmentService(),
            $this->createShipmentExpenseCollectionRemover()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCollectionRemoverInterface
     */
    public function createShipmentExpenseCollectionRemover(): ShipmentExpenseCollectionRemoverInterface
    {
        return new ShipmentExpenseCollectionRemover($this->getShipmentService());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface
     */
    protected function createMethodPrice()
    {
        return new MethodPrice(
            $this->getQueryContainer()
        );
    }

    /**
     * @return array
     */
    protected function getPlugins()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS);
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected function getMethodFilterPlugins()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::SHIPMENT_METHOD_FILTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface[]
     */
    protected function getShipmentGroupsSanitizerPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::SHIPMENT_GROUPS_SANITIZER_PLUGINS);
    }

    /**
     * @deprecated Use createCheckoutShipmentOrderSaver() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaverInterface
     */
    public function createShipmentOrderSaver()
    {
        return new ShipmentOrderSaver(
            $this->getEntityManager(),
            $this->createExpenseSanitizer(),
            $this->getRepository()
        );
    }

    /**
     * @deprecated Use createCheckoutMultiShipmentOrderSaver() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface
     */
    public function createCheckoutShipmentOrderSaver()
    {
        return new CheckoutShipmentOrderSaver(
            $this->getEntityManager(),
            $this->createExpenseSanitizer(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface
     */
    public function createCheckoutMultiShipmentOrderSaver(): MultiShipmentOrderSaverInterface
    {
        return new MultiShipmentOrderSaver(
            $this->getEntityManager(),
            $this->getSalesFacade(),
            $this->getShipmentService(),
            $this->createExpenseSanitizer()
        );
    }

    /**
     * @deprecated Use createShipmentTaxCalculatorWithItemShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    public function createShipmentTaxCalculator()
    {
        return new ShipmentTaxRateCalculator(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface
     */
    public function createShipmentTaxCalculatorWithItemShipmentTaxRate(): CalculatorInterface
    {
        return new ShipmentTaxRateCalculatorWithItemShipmentTaxRate(
            $this->getRepository(),
            $this->getTaxFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected function getSalesFacade(): ShipmentToSalesFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_SALES);
    }

    /**
     * @deprecated Use createMultipleShipmentOrderHydrate() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderHydrateInterface
     */
    public function createShipmentOrderHydrate()
    {
        return new ShipmentOrderHydrate($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Shipment\ShipmentOrderHydrateInterface
     */
    public function createMultipleShipmentOrderHydrate(): ShipmentOrderHydrateInterface
    {
        return new MultipleShipmentOrderHydrate($this->getRepository(), $this->getSalesFacade());
    }

    /**
     * @deprecated Use getSalesFacade() instead.
     *
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentServiceInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createShipmentTaxCalculatorWithItemShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface
     */
    public function createShipmentTaxCalculatorStrategyResolver(): TaxRateCalculatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[TaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentTaxCalculator();
        };

        $strategyContainer[TaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentTaxCalculatorWithItemShipmentTaxRate();
        };

        return new TaxRateCalculatorStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createCheckoutMultiShipmentOrderSaver() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolverInterface
     */
    public function createCheckoutShipmentOrderSaverStrategyResolver(): OrderSaverStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutShipmentOrderSaver();
        };

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutMultiShipmentOrderSaver();
        };

        return new OrderSaverStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createMultiShipmentExpenseFilter() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\StrategyResolver\ShipmentExpenseFilterStrategyResolverInterface
     */
    public function createShipmentExpenseFilterStrategyResolver(): ShipmentExpenseFilterStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentExpenseFilter();
        };

        $strategyContainer[OrderSaverStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentExpenseFilter();
        };

        return new ShipmentExpenseFilterStrategyResolver($strategyContainer);
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface
     */
    public function createShipmentFetcher(): ShipmentFetcherInterface
    {
        return new ShipmentFetcher(
            $this->getQueryContainer(),
            $this->getCurrencyFacade(),
            $this->createShipmentMethodTransformer()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface
     */
    public function createShipmentMethodExpander(): ShipmentMethodExpanderInterface
    {
        return new ShipmentMethodExpander(
            $this->createShipmentFetcher(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Shipment\ShipmentReaderInterface
     */
    public function createShipmentReader(): ShipmentReaderInterface
    {
        return new ShipmentReader(
            $this->getSalesFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Shipment\ShipmentSaverInterface
     */
    public function createShipmentSaver(): ShipmentSaverInterface
    {
        return new ShipmentSaver(
            $this->createCheckoutMultiShipmentOrderSaver(),
            $this->createShipmentMethodExpander(),
            $this->getShipmentService(),
            $this->createShipmentExpenseCreator()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreatorInterface
     */
    public function createShipmentExpenseCreator(): ShipmentExpenseCreatorInterface
    {
        return new ShipmentExpenseCreator(
            $this->createShipmentMapper(),
            $this->createExpenseSanitizer()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    public function createExpenseSanitizer(): ExpenseSanitizerInterface
    {
        return new ExpenseSanitizer($this->getPriceFacade());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface
     */
    public function createShipmentMethodAvailabilityChecker(): MethodAvailabilityCheckerInterface
    {
        return new MethodAvailabilityChecker($this->getAvailabilityPlugins());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface
     */
    public function createShipmentMethodPriceReader(): MethodPriceReaderInterface
    {
        return new MethodPriceReader(
            $this->getPricePlugins(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupCreatorInterface
     */
    public function createShipmentGroupCreator(): ShipmentGroupCreatorInterface
    {
        return new ShipmentGroupCreator($this->getRepository(), $this->getShipmentService(), $this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    public function createShipmentMapper(): ShipmentMapperInterface
    {
        return new ShipmentMapper();
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface
     */
    public function createShipmentMethodDeliveryTimeReader(): MethodDeliveryTimeReaderInterface
    {
        return new MethodDeliveryTimeReader($this->getDeliveryTimePlugins());
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface[]
     */
    public function getAvailabilityPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::AVAILABILITY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface[]
     */
    public function getPricePlugins(): array
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::PRICE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface[]|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface[]
     */
    public function getDeliveryTimePlugins(): array
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeInterface
     */
    public function getPriceFacade(): ShipmentToPriceFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface
     */
    public function getCalculationFacade(): ShipmentToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpanderInterface
     */
    public function createQuoteShipmentExpander(): QuoteShipmentExpanderInterface
    {
        return new QuoteShipmentExpander(
            $this->getShipmentService(),
            $this->createMethodReader(),
            $this->createExpenseSanitizer(),
            $this->createShipmentMapper(),
            $this->getCalculationFacade(),
            $this->getShipmentGroupsSanitizerPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\OrderItem\ShipmentSalesOrderItemReaderInterface
     */
    public function createShipmentSalesOrderItemReader(): ShipmentSalesOrderItemReaderInterface
    {
        return new ShipmentSalesOrderItemReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Mail\ShipmentOrderMailExpanderInterface
     */
    public function createShipmentOrderMailExpander(): ShipmentOrderMailExpanderInterface
    {
        return new ShipmentOrderMailExpander($this->getShipmentService());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Event\ShipmentEventGrouperInterface
     */
    public function createShipmentEventGrouper(): ShipmentEventGrouperInterface
    {
        return new ShipmentEventGrouper($this->getShipmentService());
    }
}
