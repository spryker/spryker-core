<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

interface ProductPageSearchRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfers(array $productIds): array;

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityTransfers(array $productIds): array;
}
