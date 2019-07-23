<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductListProductAbstractStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;

interface ProductListProductAbstractStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer|null
     */
    public function findProductAbstractProductListStorage(int $idProductAbstract): ?ProductAbstractProductListStorageTransfer;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer[]
     */
    public function getProductAbstractProductListStorageTransfersByProductAbstractIds(array $productAbstractIds): array;
}
