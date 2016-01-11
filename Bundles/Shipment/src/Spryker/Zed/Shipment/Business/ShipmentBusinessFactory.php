<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Shipment\ShipmentConfig;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 */
class ShipmentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\Carrier
     */
    public function createCarrier()
    {
        return new Carrier();
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\Method
     */
    public function createMethod()
    {
        return new Method(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS)
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver
     */
    public function createShipmentOrderSaver()
    {
        return new ShipmentOrderSaver($this->getSalesQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_SALES);
    }

}
