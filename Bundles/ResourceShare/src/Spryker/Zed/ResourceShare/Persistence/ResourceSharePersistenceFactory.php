<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ResourceShare\Persistence\Propel\Mapper\ResourceShareMapper;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface getRepository()
 * @method \Spryker\Zed\ResourceShare\ResourceShareConfig getConfig()
 */
class ResourceSharePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ResourceShare\Persistence\SpyResourceShareQuery
     */
    public function createResourceSharePropelQuery(): SpyResourceShareQuery
    {
        return SpyResourceShareQuery::create();
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Persistence\Propel\Mapper\ResourceShareMapper
     */
    public function createResourceShareMapper(): ResourceShareMapper
    {
        return new ResourceShareMapper();
    }
}
