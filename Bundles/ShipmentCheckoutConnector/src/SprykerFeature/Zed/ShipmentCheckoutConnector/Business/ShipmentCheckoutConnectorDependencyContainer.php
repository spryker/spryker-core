<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydratorInterface;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaverInterface;
use SprykerFeature\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;

/**
 * @method ShipmentCheckoutConnectorBusiness getFactory()
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

        return $this->getFactory()->createModelShipmentOrderHydrator($queryContainer);
    }

    /**
     * @return ShipmentOrderSaverInterface
     */
    public function createShipmentOrderSaver()
    {
        $queryContainer = $this->getQueryContainer();

        return $this->getFactory()->createModelShipmentOrderSaver($queryContainer);
    }

}
