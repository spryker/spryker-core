<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper;

/**
 * @method \Spryker\Zed\ServicePoint\ServicePointConfig getConfig()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface getEntityManager()
 */
class ServicePointPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    public function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery
     */
    public function getServicePointStoreQuery(): SpyServicePointStoreQuery
    {
        return SpyServicePointStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper
     */
    public function createServicePointMapper(): ServicePointMapper
    {
        return new ServicePointMapper();
    }
}
