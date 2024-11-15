<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\DecimalObject\Decimal;

interface ItemQuantityCalculatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateTotalItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): Decimal;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): int;
}
