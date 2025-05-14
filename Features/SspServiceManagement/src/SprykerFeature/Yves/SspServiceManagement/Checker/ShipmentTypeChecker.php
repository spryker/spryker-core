<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Checker;

use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig;

class ShipmentTypeChecker implements ShipmentTypeCheckerInterface
{
    /**
     * @param \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig $sspServiceManagementConfig
     */
    public function __construct(protected SspServiceManagementConfig $sspServiceManagementConfig)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasOnlyServiceShipmentType(array $shipmentTypes): bool
    {
        if (count($shipmentTypes) !== 1) {
            return false;
        }

        $shipmentType = reset($shipmentTypes);

        return $shipmentType->getKey() === $this->sspServiceManagementConfig::SHIPMENT_TYPE_IN_CENTER_SERVICE;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasOnlyDeliveryShipmentType(array $shipmentTypes): bool
    {
        if (count($shipmentTypes) !== 1) {
            return false;
        }

        $shipmentType = reset($shipmentTypes);

        return $shipmentType->getKey() === $this->sspServiceManagementConfig::SHIPMENT_TYPE_DELIVERY;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasShipmentTypeWithRequiredLocation(array $shipmentTypes): bool
    {
        $servicePointRequiredShipmentTypeKeys = $this->sspServiceManagementConfig->getServicePointRequiredShipmentTypeKeys();

        foreach ($shipmentTypes as $shipmentType) {
            if (in_array($shipmentType->getKey(), $servicePointRequiredShipmentTypeKeys, true)) {
                return true;
            }
        }

        return false;
    }
}
