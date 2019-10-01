<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentGroup;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentGroupMethodFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject;
}
