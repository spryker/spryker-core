<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WarehouseAllocation\Business\Allocator\WarehouseAllocator;
use Spryker\Zed\WarehouseAllocation\Business\Allocator\WarehouseAllocatorInterface;
use Spryker\Zed\WarehouseAllocation\Business\Expander\WarehouseExpander;
use Spryker\Zed\WarehouseAllocation\Business\Expander\WarehouseExpanderInterface;
use Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapper;
use Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapperInterface;
use Spryker\Zed\WarehouseAllocation\WarehouseAllocationDependencyProvider;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface getRepository()()
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class WarehouseAllocationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\WarehouseAllocation\Business\Expander\WarehouseExpanderInterface
     */
    public function createWarehouseExpander(): WarehouseExpanderInterface
    {
        return new WarehouseExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapperInterface
     */
    public function createWarehouseAllocationOrderMapper(): WarehouseAllocationOrderMapperInterface
    {
        return new WarehouseAllocationOrderMapper();
    }

    /**
     * @return \Spryker\Zed\WarehouseAllocation\Business\Allocator\WarehouseAllocatorInterface
     */
    public function createWarehouseAllocator(): WarehouseAllocatorInterface
    {
        return new WarehouseAllocator(
            $this->getEntityManager(),
            $this->createWarehouseAllocationOrderMapper(),
            $this->getSalesOrderWarehouseAllocationPlugins(),
        );
    }

    /**
     * @return list<\Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface>
     */
    public function getSalesOrderWarehouseAllocationPlugins(): array
    {
        return $this->getProvidedDependency(
            WarehouseAllocationDependencyProvider::PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION,
        );
    }
}
