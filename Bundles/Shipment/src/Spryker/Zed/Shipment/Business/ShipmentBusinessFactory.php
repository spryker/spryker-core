<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Shipment\Business\Calculator\QuoteDataBCForMultiShipmentAdapter as ShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\Shipment\Business\Calculator\QuoteDataBCForMultiShipmentAdapterInterface as ShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\Shipment\Business\Calculator\ShipmentTaxRateCalculator as ShipmentTaxRateCalculatorWithItemShipmentTaxRate;
use Spryker\Zed\Shipment\Business\Checkout\QuoteDataBCForMultiShipmentAdapter as ShipmentOrderSaverQuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\Shipment\Business\Checkout\QuoteDataBCForMultiShipmentAdapterInterface as ShipmentOrderSaverQuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaver as CheckoutShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverWithMultiShippingAddress;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\MethodPrice;
use Spryker\Zed\Shipment\Business\Model\ShipmentCarrierReader;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderHydrate;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformer;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupSaver;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupSaverInterface;
use Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolver;
use Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolverInterface;
use Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolver;
use Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
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
     * @return \Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected function getMethodFilterPlugins()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::SHIPMENT_METHOD_FILTER_PLUGINS);
    }

    /**
     * @deprecated Use createCheckoutShipmentOrderSaver() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaverInterface
     */
    public function createShipmentOrderSaver()
    {
        return new ShipmentOrderSaver($this->getSalesQueryContainer());
    }

    /**
     * @deprecated Use createCheckoutShipmentOrderSaverWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface
     */
    public function createCheckoutShipmentOrderSaver()
    {
        return new CheckoutShipmentOrderSaver($this->getSalesQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface
     */
    public function createCheckoutShipmentOrderSaverWithMultiShippingAddress(): ShipmentOrderSaverInterface
    {
        return new ShipmentOrderSaverWithMultiShippingAddress(
            $this->getEntityManager(),
            $this->getSalesFacade(),
            $this->getCustomerFacade(),
            $this->getShipmentService(),
            $this->createShipmentOrderSaverQuoteDataBCForMultiShipmentAdapter()
        );
    }

    /**
     * @deprecated Use createShipmentTaxCalculatorWithItemShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    public function createShipmentTaxCalculator()
    {
        return new ShipmentTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Calculator\CalculatorInterface
     */
    public function createShipmentTaxCalculatorWithItemShipmentTaxRate(): CalculatorInterface
    {
        return new ShipmentTaxRateCalculatorWithItemShipmentTaxRate(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->createShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapter()
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
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface
     */
    protected function getCustomerFacade(): ShipmentToCustomerInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_CUSTOMER);
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
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderHydrateInterface
     */
    public function createShipmentOrderHydrate()
    {
        return new ShipmentOrderHydrate($this->getQueryContainer());
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
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\Shipment\Business\Calculator\QuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapter(): ShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapterInterface
    {
        return new ShipmentTaxRateCalculatorQuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\Shipment\Business\Checkout\QuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createShipmentOrderSaverQuoteDataBCForMultiShipmentAdapter(): ShipmentOrderSaverQuoteDataBCForMultiShipmentAdapterInterface
    {
        return new ShipmentOrderSaverQuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createShipmentTaxCalculatorWithItemShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface
     */
    public function createShipmentTaxCalculatorStrategyResolver(): TaxRateCalculatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addStrategySalesOrderSaverWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addStrategySalesOrderSaverWithMultipleShippingAddress($strategyContainer);

        return new TaxRateCalculatorStrategyResolver($strategyContainer);
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
        $strategyContainer[TaxRateCalculatorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentTaxCalculator();
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
        $strategyContainer[TaxRateCalculatorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentTaxCalculatorWithItemShipmentTaxRate();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createCheckoutShipmentOrderSaverWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\StrategyResolver\OrderSaverStrategyResolverInterface
     */
    public function createCheckoutShipmentOrderSaverStrategyResolver(): OrderSaverStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addCheckoutShipmentOrderSaverWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addCheckoutShipmentOrderSaverWithMultipleShippingAddress($strategyContainer);

        return new OrderSaverStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addCheckoutShipmentOrderSaverWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderSaverStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutShipmentOrderSaver();
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
    protected function addCheckoutShipmentOrderSaverWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[OrderSaverStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCheckoutShipmentOrderSaverWithMultiShippingAddress();
        };

        return $strategyContainer;
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentGroupSaverInterface
     */
    public function createShipmentGroupSaver(): ShipmentGroupSaverInterface
    {
        return new ShipmentGroupSaver(
            $this->getEntityManager(),
            $this->getSalesFacade(),
            $this->getQueryContainer(),
            $this->getStoreFacade(),
            $this->createShipmentMethodTransformer(),
            $this->getCurrencyFacade()
        );
    }
}
