<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;

interface ProductListProductConcreteStorageReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null
     */
    public function findProductConcreteProductListStorage(int $idProduct): ?ProductConcreteProductListStorageTransfer;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer[]
     */
    public function getProductConcreteProductListStorageTransfersByProductConcreteIds(array $productConcreteIds): array;
}
