<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ShipmentBusiness getFactory()
 * @method ShipmentDependencyContainer getDependencyContainer()
 */
class ShipmentFacade extends AbstractFacade
{
    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierModel = $this
            ->getDependencyContainer()
            ->createCarrierModel();

        return $carrierModel->create($carrierTransfer);
    }
}
