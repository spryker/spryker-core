<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListCategoryRelation;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;

interface ProductListCategoryRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    public function saveProductListCategoryRelation(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): ProductListCategoryRelationTransfer;
}
