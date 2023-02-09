<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence;

use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\WarehouseAllocation\Persistence\Propel\Mapper\WarehouseAllocationMapper;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface getRepository()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class WarehouseAllocationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery<\Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation>
     */
    public function createWarehouseAllocationPropelQuery(): SpyWarehouseAllocationQuery
    {
        return SpyWarehouseAllocationQuery::create();
    }

    /**
     * @return \Spryker\Zed\WarehouseAllocation\Persistence\Propel\Mapper\WarehouseAllocationMapper
     */
    public function createWarehouseAllocationMapper(): WarehouseAllocationMapper
    {
        return new WarehouseAllocationMapper();
    }
}
