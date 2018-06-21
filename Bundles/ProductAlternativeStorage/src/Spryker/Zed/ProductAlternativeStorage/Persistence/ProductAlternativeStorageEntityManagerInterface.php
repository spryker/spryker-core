<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer;

interface ProductAlternativeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductAlternativeStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductAlternativeStorageEntity(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductReplacementStorage(
        SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductReplacementStorage(
        SpyProductReplacementStorageEntityTransfer $productReplacementStorageEntityTransfer
    ): void;
}
