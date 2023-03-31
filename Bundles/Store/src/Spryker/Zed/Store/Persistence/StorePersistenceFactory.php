<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Store\Persistence\Propel\Mapper\StoreMapper;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 * @method \Spryker\Zed\Store\Persistence\StoreEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StorePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function createStoreQuery()
    {
        return SpyStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Store\Persistence\Propel\Mapper\StoreMapper
     */
    public function createStoreMapper(): StoreMapper
    {
        return new StoreMapper();
    }
}
