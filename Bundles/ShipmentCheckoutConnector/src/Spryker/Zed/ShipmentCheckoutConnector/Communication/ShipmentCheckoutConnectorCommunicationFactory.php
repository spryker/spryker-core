<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

}
