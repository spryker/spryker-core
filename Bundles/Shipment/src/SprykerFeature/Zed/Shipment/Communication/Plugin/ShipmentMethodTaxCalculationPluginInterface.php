<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

interface ShipmentMethodTaxCalculationPluginInterface
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     * @param int $defaultEffectiveTaxRate
     *
     * @return int $defaultEffectiveTaxRate
     */
    public function getTaxRate(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability, $defaultEffectiveTaxRate);

}
