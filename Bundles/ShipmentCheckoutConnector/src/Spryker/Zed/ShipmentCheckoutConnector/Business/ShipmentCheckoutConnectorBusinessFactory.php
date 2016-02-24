<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderHydrator;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentOrderSaver;

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
