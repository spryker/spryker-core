<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class ShipmentFacade extends AbstractFacade
{
    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    public function registerCustomer(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierManager = $this
            ->getDependencyContainer()
            ->createCarrierManager();

        return $carrierManager->createCarrier($carrierTransfer);
    }
}
