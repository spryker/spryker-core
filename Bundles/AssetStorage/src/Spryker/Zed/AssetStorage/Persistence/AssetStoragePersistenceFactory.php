<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage;
use Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorageQuery;
use Spryker\Zed\AssetStorage\Persistence\Mapper\AssetStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 */
class AssetStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorageQuery
     */
    public function createAssetSlotStorageQuery(): SpyAssetSlotStorageQuery
    {
        return SpyAssetSlotStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\AssetStorage\Persistence\Mapper\AssetStorageMapper
     */
    public function createAssetStorageMapper(): AssetStorageMapper
    {
        return new AssetStorageMapper();
    }

    /**
     * @return \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface
     */
    public function createRepository(): AssetStorageRepositoryInterface
    {
        return new AssetStorageRepository();
    }

    /**
     * @return \Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage
     */
    public function createSpyAssetSlotStorage(): SpyAssetSlotStorage
    {
        return new SpyAssetSlotStorage();
    }
}
