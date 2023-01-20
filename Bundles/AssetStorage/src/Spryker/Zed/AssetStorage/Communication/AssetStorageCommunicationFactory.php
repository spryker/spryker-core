<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication;

use Spryker\Zed\AssetStorage\AssetStorageDependencyProvider;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 */
class AssetStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface
     */
    public function getAssetFacade(): AssetStorageToAssetFacadeInterface
    {
        return $this->getProvidedDependency(AssetStorageDependencyProvider::FACADE_ASSET);
    }
}
