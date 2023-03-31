<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Orm\Zed\StoreStorage\Persistence\SpyStoreListStorageQuery;
use Orm\Zed\StoreStorage\Persistence\SpyStoreStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageRepositoryInterface getRepository()
 */
class StoreStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\StoreStorage\Persistence\SpyStoreStorageQuery
     */
    public function createStoreStorageQuery(): SpyStoreStorageQuery
    {
        return SpyStoreStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\StoreStorage\Persistence\SpyStoreListStorageQuery
     */
    public function createStoreListStorageQuery(): SpyStoreListStorageQuery
    {
        return SpyStoreListStorageQuery::create();
    }
}
