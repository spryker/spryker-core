<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Persistence;

use Orm\Zed\ServicePointStorage\Persistence\SpyServicePointStorageQuery;
use Orm\Zed\ServicePointStorage\Persistence\SpyServiceTypeStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ServicePointStorage\Persistence\Propel\Mapper\ServicePointStorageMapper;

/**
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageRepositoryInterface getRepository()
 */
class ServicePointStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ServicePointStorage\Persistence\SpyServicePointStorageQuery
     */
    public function getServicePointStorageQuery(): SpyServicePointStorageQuery
    {
        return SpyServicePointStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePointStorage\Persistence\SpyServiceTypeStorageQuery
     */
    public function getServiceTypeStorageQuery(): SpyServiceTypeStorageQuery
    {
        return SpyServiceTypeStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ServicePointStorage\Persistence\Propel\Mapper\ServicePointStorageMapper
     */
    public function createServicePointStorageMapper(): ServicePointStorageMapper
    {
        return new ServicePointStorageMapper();
    }
}
