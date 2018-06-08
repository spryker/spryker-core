<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;

interface ProductListProductConcreteRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     *
     * @return void
     */
    public function saveProductListProductConcreteRelation(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): void;
}
