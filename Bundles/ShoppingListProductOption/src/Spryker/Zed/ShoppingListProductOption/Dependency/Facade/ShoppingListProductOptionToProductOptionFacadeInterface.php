<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Dependency\Facade;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;

interface ShoppingListProductOptionToProductOptionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getProductOptionCollectionByCriteria(ProductOptionCriteriaTransfer $productOptionCriteriaTransfer): ProductOptionCollectionTransfer;
}
