<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\ShipmentCarrierReader;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderHydrate;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\CarrierDiscountCollector;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\CarrierDiscountCollectorInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\CarrierDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\MethodDiscountCollector;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\MethodDiscountCollectorInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\MethodDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\MethodDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\PriceDiscountCollector;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\PriceDiscountCollectorInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\PriceDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\PriceDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountConnectorReader;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReader;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReaderInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;
use Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig getConfig()
 */
class ShipmentDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ShipmentDiscountReaderInterface
     */
    public function createShipmentDiscountConnectorReader()
    {
        return new ShipmentDiscountReader(
            $this->getShipmentFacade()
        );
    }

    /**
     * @return CarrierDiscountCollectorInterface
     */
    public function createCarrierDiscountCollector()
    {
        return new CarrierDiscountCollector(
            $this->createCarrierDiscountDecisionRule()
        );
    }

    /**
     * @return CarrierDiscountDecisionRuleInterface
     */
    public function createCarrierDiscountDecisionRule()
    {
        return new CarrierDiscountDecisionRule(
            $this->getDiscountFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @return MethodDiscountCollectorInterface
     */
    public function createMethodDiscountCollector()
    {
        return new MethodDiscountCollector(
            $this->createMethodDiscountDecisionRule()
        );
    }

    /**
     * @return MethodDiscountDecisionRuleInterface
     */
    public function createMethodDiscountDecisionRule()
    {
        return new MethodDiscountDecisionRule(
            $this->getDiscountFacade()
        );
    }

    /**
     * @return PriceDiscountCollectorInterface
     */
    public function createPriceDiscountCollector()
    {
        return new PriceDiscountCollector(
            $this->createPriceDiscountDecisionRule()
        );
    }

    /**
     * @return PriceDiscountDecisionRuleInterface
     */
    public function createPriceDiscountDecisionRule()
    {
        return new PriceDiscountDecisionRule(
            $this->getDiscountFacade()
        );
    }

    /**
     * @return ShipmentDiscountConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(ShipmentDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return ShipmentDiscountConnectorToShipmentInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentDiscountConnectorDependencyProvider::FACADE_SHIPMENT);
    }

}
