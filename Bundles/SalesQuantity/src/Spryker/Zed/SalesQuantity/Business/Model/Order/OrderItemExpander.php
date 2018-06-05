<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Model\Order;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return null|\ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItem(ItemTransfer $itemTransfer): ?ArrayObject
    {
        if ($itemTransfer->getIsQuantitySplittable()) {
            return null;
        }

        return new ArrayObject([$itemTransfer]);
    }
}
