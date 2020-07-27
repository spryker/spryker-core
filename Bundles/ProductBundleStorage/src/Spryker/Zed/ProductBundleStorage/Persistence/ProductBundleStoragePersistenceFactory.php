<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
