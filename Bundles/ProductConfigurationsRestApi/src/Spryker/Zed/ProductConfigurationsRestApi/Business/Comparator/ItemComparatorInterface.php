<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface ItemComparatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return bool
     */
    public function isSameItem(ItemTransfer $itemTransfer, CartItemRequestTransfer $cartItemRequestTransfer): bool;
}
