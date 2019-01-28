<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpander as ShipmentCartExpanderWithMultiShippingAddress;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidator as ShipmentCartValidatorWithMultiShippingAddress;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorQuoteDataBCForMultiShipmentAdapter;
use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorQuoteDataBCForMultiShipmentAdapterInterface;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpander;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartValidator;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolver;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolverInterface;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolver;
use Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolverInterface;
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
            $this->createShipmentCartExpanderQuoteDataBCForMultiShipmentAdapter()
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
            $this->createShipmentCartValidatorQuoteDataBCForMultiShipmentAdapter()
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
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createShipmentCartExpanderQuoteDataBCForMultiShipmentAdapter(): ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapterInterface
    {
        return new ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapterInterface
     */
    protected function createShipmentCartValidatorQuoteDataBCForMultiShipmentAdapter(): ShipmentCartValidatorQuoteDataBCForMultiShipmentAdapterInterface
    {
        return new ShipmentCartValidatorQuoteDataBCForMultiShipmentAdapter();
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createShipmentCartExpanderWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartExpanderStrategyResolverInterface
     */
    public function createShipmentCartExpanderStrategyResolver(): CartExpanderStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentCartExpanderWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addShipmentCartExpanderWithMultipleShippingAddress($strategyContainer);

        return new CartExpanderStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentCartExpanderWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[CartExpanderStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartExpander();
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
    protected function addShipmentCartExpanderWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[CartExpanderStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartExpanderWithMultiShippingAddress();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major release. Use $this->createShipmentCartValidatorWithMultiShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver\CartValidatorStrategyResolverInterface
     */
    public function createShipmentCartValidatorStrategyResolver(): CartValidatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentCartValidatorWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addShipmentCartValidatorWithMultipleShippingAddress($strategyContainer);

        return new CartValidatorStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentCartValidatorWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[CartValidatorStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartValidate();
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
    protected function addShipmentCartValidatorWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[CartValidatorStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCartValidatorWithMultiShippingAddress();
        };

        return $strategyContainer;
    }
}
