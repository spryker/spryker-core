<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer;
use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage;

interface ProductQuantityStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage $spyProductQuantityStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity
     *
     * @return \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorage
     */
    public function hydrateSpyProductQuantityStorageEntity(
        SpyProductQuantityStorage $spyProductQuantityStorageEntity,
        SpyProductQuantityStorageEntityTransfer $productQuantityStorageEntity
    ): SpyProductQuantityStorage;
}
