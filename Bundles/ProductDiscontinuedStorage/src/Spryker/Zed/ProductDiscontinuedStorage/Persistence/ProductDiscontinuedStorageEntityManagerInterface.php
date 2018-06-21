<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer;

interface ProductDiscontinuedStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductDiscontinuedStorageEntity(
        SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinuedStorageEntity(
        SpyProductDiscontinuedStorageEntityTransfer $productDiscontinuedStorageEntityTransfer
    ): void;
}
