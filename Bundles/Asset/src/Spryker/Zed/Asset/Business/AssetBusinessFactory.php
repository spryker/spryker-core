<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business;

use Spryker\Zed\Asset\AssetDependencyProvider;
use Spryker\Zed\Asset\Business\Creator\AssetCreator;
use Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface;
use Spryker\Zed\Asset\Business\Deleter\AssetDeleter;
use Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface;
use Spryker\Zed\Asset\Business\Mapper\AssetMapper;
use Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface;
use Spryker\Zed\Asset\Business\Updater\AssetUpdater;
use Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 * @method \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface getEntityManager()
 */
class AssetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface
     */
    public function createAssetCreator(): AssetCreatorInterface
    {
        return new AssetCreator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createAssetMapper(),
            $this->getStoreReferenceFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface
     */
    public function createAssetUpdater(): AssetUpdaterInterface
    {
        return new AssetUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreReferenceFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Asset\Business\Deleter\AssetDeleterInterface
     */
    public function createAssetDeleter(): AssetDeleterInterface
    {
        return new AssetDeleter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreReferenceFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreReferenceInterface
     */
    public function getStoreReferenceFacade(): AssetToStoreReferenceInterface
    {
        return $this->getProvidedDependency(AssetDependencyProvider::FACADE_STORE_REFERENCE);
    }

    /**
     * @return \Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface
     */
    protected function createAssetMapper(): AssetMapperInterface
    {
        return new AssetMapper();
    }
}
