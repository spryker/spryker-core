<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger\DataMerger;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductStoreDataMerger extends AbstractProductDataMerger
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function doMerge(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        if ($productConcreteTransfer->getStores()->count() === 0 && $productAbstractTransfer->getStoreRelation()) {
            $productConcreteTransfer->setStores($productAbstractTransfer->getStoreRelation()->getStores());
        }

        return $productConcreteTransfer;
    }
}
