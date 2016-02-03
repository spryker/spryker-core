<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydrator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig getConfig()
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainer getQueryContainer()
 */
class ShipmentCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydratorInterface
     */
    public function createShipmentOrderHydrator()
    {
        $queryContainer = $this->getQueryContainer();

        return new ShipmentOrderHydrator($queryContainer);
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaverInterface
     */
    public function createShipmentOrderSaver()
    {
        $queryContainer = $this->getQueryContainer();

        return new ShipmentOrderSaver($queryContainer);
    }

}
