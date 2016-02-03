<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig getConfig()
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainer getQueryContainer()
 */
class ShipmentCheckoutConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(
            ShipmentCheckoutConnectorDependencyProvider::FACADE_SHIPMENT
        );
    }

    /**
     * @deprecated Use getShipmentFacade() instead.
     *
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade
     */
    public function createShipmentFacade()
    {
        trigger_error('Deprecated, use getShipmentFacade() instead.', E_USER_DEPRECATED);

        return $this->getShipmentFacade();
    }

}
