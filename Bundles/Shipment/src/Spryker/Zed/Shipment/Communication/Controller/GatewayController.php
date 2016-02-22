<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethodsAction(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability)
    {
        return $this->getFacade()
            ->getAvailableMethods($shipmentMethodAvailability);
    }

}
