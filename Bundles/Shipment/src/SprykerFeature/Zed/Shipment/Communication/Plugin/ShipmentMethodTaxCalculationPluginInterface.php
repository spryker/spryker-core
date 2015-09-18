<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;

interface ShipmentMethodTaxCalculationPluginInterface
{

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     * @param int $defaultEffectiveTaxRate
     *
     * @return int $defaultEffectiveTaxRate
     */
    public function getTaxRate(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability, $defaultEffectiveTaxRate);

}
