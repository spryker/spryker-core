<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListProductOptionWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function saveShoppingListItemProductOption(ShoppingListItemTransfer $shoppingListItemTransfer): void;
}
