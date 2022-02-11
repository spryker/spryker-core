<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

interface ProductAbstractStorageCollectionFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    public function filterProductAbstractStorageCollection(array $productAbstractStorageTransfers): array;
}
