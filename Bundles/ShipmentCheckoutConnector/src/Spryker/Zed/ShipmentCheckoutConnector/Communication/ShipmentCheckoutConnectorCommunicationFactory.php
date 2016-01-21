<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;
use Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;
use Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainer;

/**
 * @method ShipmentCheckoutConnectorConfig getConfig()
 * @method ShipmentCheckoutConnectorQueryContainer getQueryContainer()
 */
class ShipmentCheckoutConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ShipmentFacade
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
     * @return ShipmentFacade
     */
    public function createShipmentFacade()
    {
        trigger_error('Deprecated, use getShipmentFacade() instead.', E_USER_DEPRECATED);

        return $this->getShipmentFacade();
    }

}
