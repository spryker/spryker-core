<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorDependencyContainer;

/**
 * @method ShipmentCheckoutConnectorDependencyContainer getDependencyContainer()
 * TODO: Which interface should I implement?
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{

    /**
     * @param OrderTransfer $orderTransfer
     */
    public function getAvailableMethods(OrderTransfer $orderTransfer){
        $this->getDependencyContainer()->createShipmentFacade()->getAvailableMethods($orderTransfer);
    }

}
