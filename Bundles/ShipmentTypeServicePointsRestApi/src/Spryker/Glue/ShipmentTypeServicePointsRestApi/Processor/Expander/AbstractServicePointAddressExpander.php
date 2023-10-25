<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\ServicePointAddressStorageTransfer;

abstract class AbstractServicePointAddressExpander implements ServicePointAddressExpanderInterface
{
    /**
     * @param int $idShipmentMethod
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $applicableShipmentTypeStorageTransfers
     *
     * @return bool
     */
    protected function isApplicableShipmentMethod(int $idShipmentMethod, array $applicableShipmentTypeStorageTransfers): bool
    {
        foreach ($applicableShipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            if (in_array($idShipmentMethod, $shipmentTypeStorageTransfer->getShipmentMethodIds(), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerTransfer $restCustomerTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestAddressTransfer
     */
    protected function createRestShippingAddressTransfer(
        RestCustomerTransfer $restCustomerTransfer,
        ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer
    ): RestAddressTransfer {
        return (new RestAddressTransfer())
            ->fromArray($servicePointAddressStorageTransfer->toArray(), true)
            ->fromArray($restCustomerTransfer->toArray(), true)
            ->setIso2Code($servicePointAddressStorageTransfer->getCountryOrFail()->getIso2CodeOrFail());
    }
}
