<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use Generated\Shared\Cart\CartInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorDependencyContainer;

/**
 * @method ShipmentCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @internal param OrderTransfer $orderTransfer
     */
    public function getAvailableMethods(CartInterface $cartTransfer)
    {
        $this->getDependencyContainer()->createShipmentFacade()->getAvailableMethods($cartTransfer);
    }

}
