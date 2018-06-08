<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;

interface ProductListCategoryRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return void
     */
    public function saveProductListCategoryRelation(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): void;
}
