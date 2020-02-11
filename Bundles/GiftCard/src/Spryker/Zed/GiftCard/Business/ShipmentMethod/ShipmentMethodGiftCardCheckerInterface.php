<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentMethodGiftCardCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    public function containsOnlyGiftCardItems(ShipmentGroupTransfer $shipmentGroupTransfer): bool;
}
