<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesOrderItemGrouperInterface
{
    /**
     * @deprecated Use {@link getUniqueItemsFromOrder()} instead.
     *
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getUniqueOrderItems(iterable $itemTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getUniqueItemsFromOrder(OrderTransfer $orderTransfer): array;
}
