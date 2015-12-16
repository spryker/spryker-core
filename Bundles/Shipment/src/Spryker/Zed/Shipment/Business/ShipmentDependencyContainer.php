<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method ShipmentQueryContainerInterface getQueryContainer()
 */
class ShipmentDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return Carrier
     */
    public function createCarrier()
    {
        return new Carrier();
    }

    /**
     * @return Method
     */
    public function createMethod()
    {
        return new Method(
                $this->getQueryContainer(),
                $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS)
            );
    }

}
