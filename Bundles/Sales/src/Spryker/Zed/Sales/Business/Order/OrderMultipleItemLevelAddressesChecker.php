<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;


use Generated\Shared\Transfer\OrderTransfer;

class OrderMultipleItemLevelAddressesChecker implements OrderMultipleItemLevelAddressesCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function hasMultipleItemLevelAddresses(OrderTransfer $orderTransfer): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }
}