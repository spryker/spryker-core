<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;

interface ProductListCategoryRelationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    public function getProductListCategoryRelation(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): ProductListCategoryRelationTransfer;
}
