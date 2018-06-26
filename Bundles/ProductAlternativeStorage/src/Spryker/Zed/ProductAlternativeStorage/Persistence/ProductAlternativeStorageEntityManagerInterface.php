<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage;

interface ProductAlternativeStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     *
     * @return void
     */
    public function saveProductAlternativeStorageEntity(
        SpyProductAlternativeStorage $productAlternativeStorageEntity
    ): void;

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     *
     * @return void
     */
    public function deleteProductAlternativeStorageEntity(
        SpyProductAlternativeStorage $productAlternativeStorageEntity
    ): void;

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage $productReplacementStorageForEntity
     *
     * @return void
     */
    public function saveProductReplacementForStorage(
        SpyProductReplacementForStorage $productReplacementStorageForEntity
    ): void;

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage $productReplacementStorageForEntity
     *
     * @return void
     */
    public function deleteProductReplacementForStorage(
        SpyProductReplacementForStorage $productReplacementStorageForEntity
    ): void;
}
