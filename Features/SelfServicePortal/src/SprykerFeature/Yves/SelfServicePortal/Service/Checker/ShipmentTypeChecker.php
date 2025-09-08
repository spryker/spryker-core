<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Checker;

use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class ShipmentTypeChecker implements ShipmentTypeCheckerInterface
{
    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
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

        return $shipmentType->getKey() === $this->selfServicePortalConfig->getShipmentTypeInCenterService();
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasOnlyDeliveryLikeShipmentType(array $shipmentTypes): bool
    {
        if (count($shipmentTypes) !== 1) {
            return false;
        }

        $shipmentType = reset($shipmentTypes);
        $deliveryLikeTypes = $this->selfServicePortalConfig->getDeliveryLikeShipmentTypes();

        return in_array($shipmentType->getKey(), $deliveryLikeTypes, true);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasShipmentTypeWithRequiredLocation(array $shipmentTypes): bool
    {
        $servicePointRequiredShipmentTypeKeys = $this->selfServicePortalConfig->getShipmentTypeKeysRequiringServicePoint();

        foreach ($shipmentTypes as $shipmentType) {
            if (in_array($shipmentType->getKey(), $servicePointRequiredShipmentTypeKeys, true)) {
                return true;
            }
        }

        return false;
    }
}
