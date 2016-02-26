<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

interface ShipmentClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability);

}
