<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpander as ShipmentCartExpanderWithMultiShippingAddress;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidator as ShipmentCartValidatorWithMultiShippingAddress;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpander;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartValidator;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolver;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolverInterface;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolver;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolverInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface;
use Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorConfig getConfig()
 */
class ShipmentCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @deprecated Use createShipmentCartExpanderWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderInterface
     */
    public function createShipmentCartExpander()
    {
        return new ShipmentCartExpander($this->getShipmentFacade(), $this->getPriceFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface
     */
    public function createShipmentCartExpanderWithMultiShippingAddress(): ShipmentCartExpanderInterface
    {
        return new ShipmentCartExpanderWithMultiShippingAddress(
            $this->getShipmentFacade(),
            $this->getPriceFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @deprecated Use createShipmentCartValidatorWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartValidatorInterface
     */
    public function createShipmentCartValidate()
    {
        return new ShipmentCartValidator($this->getShipmentFacade(), $this->getPriceFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorInterface
     */
    public function createShipmentCartValidatorWithMultiShippingAddress(): ShipmentCartValidatorInterface
    {
        return new ShipmentCartValidatorWithMultiShippingAddress(
            $this->getShipmentFacade(),
            $this->getPriceFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentCartConnectorDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ShipmentCartConnectorDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentCartConnectorToShipmentServiceInterface
    {
        return $this->getProvidedDependency(ShipmentCartConnectorDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createShipmentCartExpanderWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolverInterface
     */
    public function createShipmentCartExpanderStrategyResolver(): CartExpanderStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[CartExpanderStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartExpander();
        };

        $strategyContainer[CartExpanderStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartExpanderWithMultiShippingAddress();
        };

        return new CartExpanderStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createShipmentCartValidatorWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolverInterface
     */
    public function createShipmentCartValidatorStrategyResolver(): CartValidatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[CartValidatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartValidate();
        };

        $strategyContainer[CartValidatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartValidatorWithMultiShippingAddress();
        };

        return new CartValidatorStrategyResolver($strategyContainer);
    }
}
