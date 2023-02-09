<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductWarehouseAllocationExample\Business\Allocator\SalesOrderWarehouseAllocator;
use Spryker\Zed\ProductWarehouseAllocationExample\Business\Allocator\SalesOrderWarehouseAllocatorInterface;

/**
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\ProductWarehouseAllocationExampleConfig getConfig()
 */
class ProductWarehouseAllocationExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductWarehouseAllocationExample\Business\Allocator\SalesOrderWarehouseAllocatorInterface
     */
    public function createSalesOrderWarehouseAllocator(): SalesOrderWarehouseAllocatorInterface
    {
        return new SalesOrderWarehouseAllocator($this->getRepository());
    }
}
