<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage;

interface ProductDiscontinuedStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity
     *
     * @return void
     */
    public function saveProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void;

    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity
     *
     * @return void
     */
    public function deleteProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void;
}
