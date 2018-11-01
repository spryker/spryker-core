<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentCheckoutPreCheck;
use Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig getConfig()
 */
class ShipmentCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentCheckoutPreCheckInterface
     */
    public function createShipmentCheckoutPreCheck()
    {
        return new ShipmentCheckoutPreCheck($this->getShipmentFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentCheckoutConnectorDependencyProvider::FACADE_SHIPMENT);
    }
}
