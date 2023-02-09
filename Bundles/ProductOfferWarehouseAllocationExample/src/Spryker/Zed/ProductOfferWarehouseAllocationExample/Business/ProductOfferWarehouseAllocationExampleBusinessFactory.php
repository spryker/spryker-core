<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferWarehouseAllocationExample\Business\Allocator\WarehouseAllocator;
use Spryker\Zed\ProductOfferWarehouseAllocationExample\Business\Allocator\WarehouseAllocatorInterface;

/**
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\Persistence\ProductOfferWarehouseAllocationExampleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\ProductOfferWarehouseAllocationExampleConfig getConfig()
 */
class ProductOfferWarehouseAllocationExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferWarehouseAllocationExample\Business\Allocator\WarehouseAllocatorInterface
     */
    public function createWarehouseAllocator(): WarehouseAllocatorInterface
    {
        return new WarehouseAllocator($this->getRepository());
    }
}
