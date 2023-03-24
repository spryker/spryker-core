<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence;

use Orm\Zed\PickingList\Persistence\SpyPickingListItemQuery;
use Orm\Zed\PickingList\Persistence\SpyPickingListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListItemMapper;
use Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListMapper;
use Spryker\Zed\PickingList\Persistence\Propel\Mapper\UserMapper;
use Spryker\Zed\PickingList\Persistence\Propel\Mapper\WarehouseMapper;

/**
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface getRepository()
 * @method \Spryker\Zed\PickingList\Persistence\PickingListEntityManagerInterface getEntityManager()
 */
class PickingListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingListQuery
     */
    public function createPickingListQuery(): SpyPickingListQuery
    {
        return SpyPickingListQuery::create();
    }

    /**
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingListItemQuery
     */
    public function createPickingListItemQuery(): SpyPickingListItemQuery
    {
        return SpyPickingListItemQuery::create();
    }

    /**
     * @return \Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListMapper
     */
    public function createPickingListMapper(): PickingListMapper
    {
        return new PickingListMapper(
            $this->createPickingListItemMapper(),
            $this->createWarehouseMapper(),
            $this->createUserMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListItemMapper
     */
    public function createPickingListItemMapper(): PickingListItemMapper
    {
        return new PickingListItemMapper();
    }

    /**
     * @return \Spryker\Zed\PickingList\Persistence\Propel\Mapper\WarehouseMapper
     */
    public function createWarehouseMapper(): WarehouseMapper
    {
        return new WarehouseMapper();
    }

    /**
     * @return \Spryker\Zed\PickingList\Persistence\Propel\Mapper\UserMapper
     */
    public function createUserMapper(): UserMapper
    {
        return new UserMapper();
    }
}
