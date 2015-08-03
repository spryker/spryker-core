<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;

class ShipmentCheckoutConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ShipmentFacade
     */
    public function createShipmentFacade()
    {
        return $this->getProvidedDependency(
            ShipmentCheckoutConnectorDependencyProvider::FACADE_SHIPMENT
        );
    }

}
