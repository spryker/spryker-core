<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

interface ShipmentMethodTaxCalculationPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     * @param int $defaultEffectiveTaxRate
     *
     * @return int $defaultEffectiveTaxRate
     */
    public function getTaxRate(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability, $defaultEffectiveTaxRate);

}
