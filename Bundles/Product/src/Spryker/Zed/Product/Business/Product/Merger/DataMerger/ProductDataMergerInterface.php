<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger\DataMerger;

interface ProductDataMergerInterface
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
    ): array;
}
