<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business;

use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaver;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydrator;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydratorInterface;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaverInterface;
use SprykerFeature\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;

/**
 * @method ShipmentCheckoutConnectorConfig getConfig()
 */
class ShipmentCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ShipmentOrderHydratorInterface
     */
    public function createShipmentOrderHydrator()
    {
        $queryContainer = $this->getQueryContainer();

        return new ShipmentOrderHydrator($queryContainer);
    }

    /**
     * @return ShipmentOrderSaverInterface
     */
    public function createShipmentOrderSaver()
    {
        $queryContainer = $this->getQueryContainer();

        return new ShipmentOrderSaver($queryContainer);
    }

}
