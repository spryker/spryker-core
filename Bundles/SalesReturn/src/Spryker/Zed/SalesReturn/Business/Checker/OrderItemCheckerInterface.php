<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Checker;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

interface OrderItemCheckerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function isOrderItemsInReturnableStates(ArrayObject $itemTransfers): bool;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isOrderItemInReturnableStates(ItemTransfer $itemTransfer): bool;
}
