<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Transfer\CartTransfer;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;
use SprykerFeature\Zed\Shipment\Communication\ShipmentDependencyContainer;

/**
 * @method ShipmentFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CartInterface $cartTransfer
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethodsAction(CartInterface $cartTransfer)
    {
        return $this->getFacade()
            ->getAvailableMethods($cartTransfer)
            ;
    }
}
