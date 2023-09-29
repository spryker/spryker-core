<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Validator;

use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

abstract class AbstractShipmentTypeCheckoutDataValidator
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isValidShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer, StoreTransfer $storeTransfer): bool
    {
        return $shipmentTypeTransfer->getIsActive() && $this->hasStoreRelation($shipmentTypeTransfer, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function hasStoreRelation(ShipmentTypeTransfer $shipmentTypeTransfer, StoreTransfer $storeTransfer): bool
    {
        if (!$shipmentTypeTransfer->getStoreRelation()) {
            return false;
        }

        foreach ($shipmentTypeTransfer->getStoreRelationOrFail()->getStores() as $assignedStoreTransfer) {
            if ($assignedStoreTransfer->getIdStoreOrFail() === $storeTransfer->getIdStoreOrFail()) {
                return true;
            }
        }

        return false;
    }
}
