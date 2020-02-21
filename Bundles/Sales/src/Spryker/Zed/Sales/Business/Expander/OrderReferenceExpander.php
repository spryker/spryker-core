<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

class OrderReferenceExpander implements OrderReferenceExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandItemWithOrderReference(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->requireOrderReference();

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setOrderReference($orderTransfer->getOrderReference());
        }

        return $orderTransfer;
    }
}
