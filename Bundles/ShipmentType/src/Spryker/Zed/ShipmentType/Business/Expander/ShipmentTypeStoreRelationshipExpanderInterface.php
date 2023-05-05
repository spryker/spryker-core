<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Expander;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;

interface ShipmentTypeStoreRelationshipExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function expandShipmentTypeCollectionWithStoreRelationships(
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ShipmentTypeCollectionTransfer;
}
