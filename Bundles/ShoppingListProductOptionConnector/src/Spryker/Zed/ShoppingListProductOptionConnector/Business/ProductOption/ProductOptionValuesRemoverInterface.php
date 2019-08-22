<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface ProductOptionValuesRemoverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemProductOptionsByRemovedProductOptionValues(ProductOptionGroupTransfer $productOptionGroupTransfer): void;
}
