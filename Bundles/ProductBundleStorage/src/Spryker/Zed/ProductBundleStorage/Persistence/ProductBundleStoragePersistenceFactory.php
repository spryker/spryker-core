<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Persistence;

use Orm\Zed\ProductBundleStorage\Persistence\SpyProductBundleStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductBundleStorage\ProductBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 */
class ProductBundleStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @phpstan-return \Orm\Zed\ProductBundleStorage\Persistence\SpyProductBundleStorageQuery<mixed>
     *
     * @return \Orm\Zed\ProductBundleStorage\Persistence\SpyProductBundleStorageQuery
     */
    public function getProductBundleStoragePropelQuery(): SpyProductBundleStorageQuery
    {
        return SpyProductBundleStorageQuery::create();
    }
}
