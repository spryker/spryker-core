<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Expander;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;

interface ShipmentMethodCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expandWithShipmentType(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): ShipmentMethodCollectionTransfer;
}
