<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business;

use Spryker\Zed\AssetStorage\AssetStorageDependencyProvider;
use Spryker\Zed\AssetStorage\Business\Publisher\AssetStorageWriter;
use Spryker\Zed\AssetStorage\Business\Publisher\AssetStorageWriterInterface;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetInterface;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 */
class AssetStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AssetStorage\Business\Publisher\AssetStorageWriterInterface
     */
    public function createAssetStorageWriter(): AssetStorageWriterInterface
    {
        return new AssetStorageWriter(
            $this->getStoreFacade(),
            $this->getAssetFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): AssetStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AssetStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetInterface
     */
    public function getAssetFacade(): AssetStorageToAssetInterface
    {
        return $this->getProvidedDependency(AssetStorageDependencyProvider::FACADE_ASSET);
    }
}
