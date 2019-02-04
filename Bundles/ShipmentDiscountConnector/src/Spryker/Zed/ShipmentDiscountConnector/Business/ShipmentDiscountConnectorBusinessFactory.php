<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\QuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\QuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollector as ShipmentDiscountCollectorWithMultiShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithMultiShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\MethodDiscountDecisionRule as MethodDiscountDecisionRuleWithMultiShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentPriceDiscountDecisionRule as ShipmentPriceDiscountDecisionRuleWithMultiShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\MethodDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\ShipmentPriceDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollector;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReader;
use Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentCollectorStrategyResolver;
use Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentCollectorStrategyResolverInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentDecisionRuleStrategyResolver;
use Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentDecisionRuleStrategyResolverInterface;
use Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig getConfig()
 */
class ShipmentDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReaderInterface
     */
    public function createShipmentDiscountReader()
    {
        return new ShipmentDiscountReader(
            $this->getShipmentFacade()
        );
    }

    /**
     * @deprecated Use createCarrierDiscountDecisionRuleWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createCarrierDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createCarrierDiscountDecisionRule()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function createCarrierDiscountCollectorWithMultiShipment(): ShipmentDiscountCollectorInterface
    {
        return new ShipmentDiscountCollectorWithMultiShipment(
            $this->createCarrierDiscountDecisionRuleWithMultiShipment(),
            $this->createQuoteDataBCForMultiShipmentAdapter()
        );
    }

    /**
     * @deprecated Use createCarrierDiscountDecisionRuleWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function createCarrierDiscountDecisionRule()
    {
        return new CarrierDiscountDecisionRule(
            $this->getDiscountFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface
     */
    public function createCarrierDiscountDecisionRuleWithMultiShipment(): ShipmentDiscountDecisionRuleInterface
    {
        return new CarrierDiscountDecisionRuleWithMultiShipment(
            $this->getDiscountFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @deprecated Use createMethodDiscountCollectorWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createMethodDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createMethodDiscountDecisionRule()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function createMethodDiscountCollectorWithMultiShipment(): ShipmentDiscountCollectorInterface
    {
        return new ShipmentDiscountCollectorWithMultiShipment(
            $this->createMethodDiscountDecisionRuleWithMultiShipment(),
            $this->createQuoteDataBCForMultiShipmentAdapter()
        );
    }

    /**
     * @deprecated Use createMethodDiscountDecisionRuleWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function createMethodDiscountDecisionRule()
    {
        return new MethodDiscountDecisionRule(
            $this->getDiscountFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface
     */
    public function createMethodDiscountDecisionRuleWithMultiShipment(): ShipmentDiscountDecisionRuleInterface
    {
        return new MethodDiscountDecisionRuleWithMultiShipment(
            $this->getDiscountFacade()
        );
    }

    /**
     * @deprecated Use createShipmentPriceDiscountCollectorWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createShipmentPriceDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createShipmentPriceDiscountDecisionRule()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function createShipmentPriceDiscountCollectorWithMultiShipment(): ShipmentDiscountCollectorInterface
    {
        return new ShipmentDiscountCollectorWithMultiShipment(
            $this->createShipmentPriceDiscountDecisionRuleWithMultiShipment(),
            $this->createQuoteDataBCForMultiShipmentAdapter()
        );
    }

    /**
     * @deprecated Use createShipmentPriceDiscountDecisionRuleWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function createShipmentPriceDiscountDecisionRule()
    {
        return new ShipmentPriceDiscountDecisionRule(
            $this->getDiscountFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface
     */
    public function createShipmentPriceDiscountDecisionRuleWithMultiShipment(): ShipmentDiscountDecisionRuleInterface
    {
        return new ShipmentPriceDiscountDecisionRuleWithMultiShipment(
            $this->getDiscountFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(ShipmentDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentDiscountConnectorDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ShipmentDiscountConnectorDependencyProvider::FACADE_MONEY);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\QuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createQuoteDataBCForMultiShipmentAdapter(): QuoteDataBCForMultiShipmentAdapterInterface
    {
        return new QuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createCarrierDiscountCollectorWithMultiShipment(),
     * createMethodDiscountCollectorWithMultiShipment() or createShipmentPriceDiscountCollectorWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentCollectorStrategyResolver
     */
    public function createShipmentDiscountCollectorStrategyResolver(): MultiShipmentCollectorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentDiscountCollectorForCarrier($strategyContainer);
        $strategyContainer = $this->addShipmentDiscountCollectorForMethod($strategyContainer);
        $strategyContainer = $this->addShipmentDiscountCollectorForPrice($strategyContainer);

        return new MultiShipmentCollectorStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentDiscountCollectorForCarrier(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_CARRIER][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountCollector();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_CARRIER][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountCollectorWithMultiShipment();
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
    protected function addShipmentDiscountCollectorForMethod(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_METHOD][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createMethodDiscountCollector();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_METHOD][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMethodDiscountCollectorWithMultiShipment();
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
    protected function addShipmentDiscountCollectorForPrice(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_PRICE][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentPriceDiscountCollector();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_PRICE][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentPriceDiscountCollectorWithMultiShipment();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createCarrierDiscountDecisionRuleWithMultiShipment(),
     * createCarrierDiscountDecisionRuleWithMultiShipment() or createCarrierDiscountDecisionRuleWithMultiShipment() instead.
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver\MultiShipmentCollectorStrategyResolver
     */
    public function createShipmentDiscountDecisionRuleStrategyResolver(): MultiShipmentDecisionRuleStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentDiscountDecisionRuleForCarrier($strategyContainer);
        $strategyContainer = $this->addShipmentDiscountDecisionRuleForMethod($strategyContainer);
        $strategyContainer = $this->addShipmentDiscountDecisionRuleForPrice($strategyContainer);

        return new MultiShipmentDecisionRuleStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentDiscountDecisionRuleForCarrier(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_CARRIER][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRule();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_CARRIER][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRuleWithMultiShipment();
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
    protected function addShipmentDiscountDecisionRuleForMethod(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_METHOD][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRule();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_METHOD][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRuleWithMultiShipment();
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
    protected function addShipmentDiscountDecisionRuleForPrice(array $strategyContainer): array
    {
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_PRICE][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRule();
        };
        $strategyContainer[MultiShipmentCollectorStrategyResolverInterface::DISCOUNT_TYPE_PRICE][MultiShipmentCollectorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createCarrierDiscountDecisionRuleWithMultiShipment();
        };

        return $strategyContainer;
    }
}
