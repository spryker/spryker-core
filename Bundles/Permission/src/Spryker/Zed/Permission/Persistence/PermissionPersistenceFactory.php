<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Persistence;

use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Permission\Persistence\Propel\Mapper\PermissionMapper;

class PermissionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    public function createPermissionQuery()
    {
        return SpyPermissionQuery::create();
    }

    /**
     * @return \Spryker\Zed\Permission\Persistence\Propel\Mapper\PermissionMapper
     */
    public function createPropelPermissionMapper()
    {
        return new PermissionMapper();
    }
}
