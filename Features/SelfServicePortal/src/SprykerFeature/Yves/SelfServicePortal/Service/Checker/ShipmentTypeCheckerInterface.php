<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Checker;

interface ShipmentTypeCheckerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasOnlyServiceShipmentType(array $shipmentTypes): bool;

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasOnlyDeliveryLikeShipmentType(array $shipmentTypes): bool;

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypes
     *
     * @return bool
     */
    public function hasShipmentTypeWithRequiredLocation(array $shipmentTypes): bool;
}
