<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;

interface ShipmentTypeStorageToShipmentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollection(
        ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
    ): ShipmentMethodCollectionTransfer;
}
