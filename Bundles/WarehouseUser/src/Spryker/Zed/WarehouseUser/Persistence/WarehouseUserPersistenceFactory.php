<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Persistence;

use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\WarehouseUser\Persistence\Propel\Mapper\WarehouseUserMapper;

/**
 * @method \Spryker\Zed\WarehouseUser\WarehouseUserConfig getConfig()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface getEntityManager()
 */
class WarehouseUserPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment>
     */
    public function createWarehouseUserAssignmentQuery(): SpyWarehouseUserAssignmentQuery
    {
        return SpyWarehouseUserAssignmentQuery::create();
    }

    /**
     * @return \Spryker\Zed\WarehouseUser\Persistence\Propel\Mapper\WarehouseUserMapper
     */
    public function createWarehouseUserMapper(): WarehouseUserMapper
    {
        return new WarehouseUserMapper();
    }
}
