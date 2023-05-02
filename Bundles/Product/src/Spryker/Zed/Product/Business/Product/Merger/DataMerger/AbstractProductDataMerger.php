<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger\DataMerger;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

abstract class AbstractProductDataMerger implements ProductDataMergerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfersIndexedByProductAbstractId
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function merge(
        array $productConcreteTransfers,
        array $productAbstractTransfersIndexedByProductAbstractId
    ): array {
        foreach ($productConcreteTransfers as $key => $productConcreteTransfer) {
            if (isset($productAbstractTransfersIndexedByProductAbstractId[$productConcreteTransfer->getFkProductAbstract()])) {
                $productAbstractTransfer = $productAbstractTransfersIndexedByProductAbstractId[$productConcreteTransfer->getFkProductAbstract()];

                $this->doMerge($productConcreteTransfer, $productAbstractTransfer);
            }

            $productConcreteTransfers[$key] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    abstract protected function doMerge(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer;
}
