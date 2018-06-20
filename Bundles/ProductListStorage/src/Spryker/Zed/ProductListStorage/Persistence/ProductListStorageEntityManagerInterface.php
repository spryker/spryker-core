<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer;

interface ProductListStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductAbstractProductListStorage(SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer): void;

    /**
     * @param int $idProductAbstractProductListStorage
     *
     * @return void
     */
    public function deleteProductAbstractProductListStorage(int $idProductAbstractProductListStorage): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductConcreteProductListStorage(SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer): void;

    /**
     * @param int $idProductConcreteProductListStorage
     *
     * @return void
     */
    public function deleteProductConcreteProductListStorage(int $idProductConcreteProductListStorage): void;
}
