<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\MethodDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\PriceDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollector;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReader;
use Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig getConfig()
 */
class ShipmentDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountReaderInterface
     */
    public function createShipmentDiscountConnectorReader()
    {
        return new ShipmentDiscountReader(
            $this->getShipmentFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createCarrierDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createCarrierDiscountDecisionRule()
        );
    }

    /**
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
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createMethodDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createMethodDiscountDecisionRule()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function createMethodDiscountDecisionRule()
    {
        return new MethodDiscountDecisionRule(
            $this->getDiscountFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollectorInterface
     */
    public function createPriceDiscountCollector()
    {
        return new ShipmentDiscountCollector(
            $this->createPriceDiscountDecisionRule()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function createPriceDiscountDecisionRule()
    {
        return new PriceDiscountDecisionRule(
            $this->getDiscountFacade()
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

}
