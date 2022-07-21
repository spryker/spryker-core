<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Orm\Zed\Asset\Persistence\SpyAssetQuery;
use Orm\Zed\Asset\Persistence\SpyAssetStoreQuery;
use Spryker\Zed\Asset\Persistence\Mapper\AssetMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 * @method \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface getEntityManager()
 */
class AssetPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Asset\Persistence\SpyAssetQuery
     */
    public function createAssetQuery(): SpyAssetQuery
    {
        return SpyAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\Asset\Persistence\SpyAssetStoreQuery
     */
    public function createAssetStoreQuery(): SpyAssetStoreQuery
    {
        return SpyAssetStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Asset\Persistence\Mapper\AssetMapper
     */
    public function createAssetMapper(): AssetMapper
    {
        return new AssetMapper();
    }
}
