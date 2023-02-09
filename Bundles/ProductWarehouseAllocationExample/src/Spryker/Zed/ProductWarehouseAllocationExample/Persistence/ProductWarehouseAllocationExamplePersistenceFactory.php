<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Persistence;

use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductWarehouseAllocationExample\ProductWarehouseAllocationExampleDependencyProvider;

/**
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\ProductWarehouseAllocationExampleConfig getConfig()
 */
class ProductWarehouseAllocationExamplePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function getStockProductQuery(): SpyStockProductQuery
    {
        return $this->getProvidedDependency(ProductWarehouseAllocationExampleDependencyProvider::STOCK_PRODUCT_QUERY);
    }
}
