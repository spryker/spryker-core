<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Persistence;

use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage;

interface ProductImageResourceAliasStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage $spyProductAbstractImageStorage
     *
     * @return void
     */
    public function saveProductAbstractImageStorageEntity(SpyProductAbstractImageStorage $spyProductAbstractImageStorage): void;

    /**
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage $spyProductAbstractImageStorage
     *
     * @return void
     */
    public function saveProductConcreteImageStorageEntity(SpyProductConcreteImageStorage $spyProductAbstractImageStorage): void;
}
