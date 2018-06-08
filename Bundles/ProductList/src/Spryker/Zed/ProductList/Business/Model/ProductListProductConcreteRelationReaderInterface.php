<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;

interface ProductListProductConcreteRelationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function getProductListProductConcreteRelation(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): ProductListProductConcreteRelationTransfer;
}
