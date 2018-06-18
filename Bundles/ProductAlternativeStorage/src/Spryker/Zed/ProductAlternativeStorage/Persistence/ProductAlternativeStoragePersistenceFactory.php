<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductAlternativeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorageQuery
     */
    public function createProductAlternativeStorageQuery(): SpyProductAlternativeStorageQuery
    {
        return SpyProductAlternativeStorageQuery::create();
    }
}
