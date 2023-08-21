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
use Spryker\Zed\Asset\Business\RequestDispatcher\AssetRequestDispatcher;
use Spryker\Zed\Asset\Business\RequestDispatcher\AssetRequestDispatcherInterface;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStamp;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStampInterface;
use Spryker\Zed\Asset\Business\Updater\AssetUpdater;
use Spryker\Zed\Asset\Business\Updater\AssetUpdaterInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface;
use Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 * @method \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface getEntityManager()
 */
class AssetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Asset\Business\RequestDispatcher\AssetRequestDispatcherInterface
     */
    public function createAssetRequestDispatcher(): AssetRequestDispatcherInterface
    {
        return new AssetRequestDispatcher(
            $this->getRepository(),
            $this->createAssetCreator(),
            $this->createAssetUpdater(),
            $this->createAssetDeleter(),
            $this->createAssetTimeStamp(),
        );
    }

    /**
     * @return \Spryker\Zed\Asset\Business\Creator\AssetCreatorInterface
     */
    public function createAssetCreator(): AssetCreatorInterface
    {
        return new AssetCreator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createAssetMapper(),
            $this->getStoreFacade(),
            $this->getEventFacade(),
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
            $this->getStoreFacade(),
            $this->getEventFacade(),
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
            $this->createAssetMapper(),
            $this->getStoreFacade(),
            $this->getEventFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStampInterface
     */
    public function createAssetTimeStamp(): AssetTimeStampInterface
    {
        return new AssetTimeStamp();
    }

    /**
     * @return \Spryker\Zed\Asset\Dependency\Facade\AssetToStoreInterface
     */
    public function getStoreFacade(): AssetToStoreInterface
    {
        return $this->getProvidedDependency(AssetDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Asset\Business\Mapper\AssetMapperInterface
     */
    public function createAssetMapper(): AssetMapperInterface
    {
        return new AssetMapper();
    }

    /**
     * @return \Spryker\Zed\Asset\Dependency\Facade\AssetToEventFacadeInterface
     */
    public function getEventFacade(): AssetToEventFacadeInterface
    {
        return $this->getProvidedDependency(AssetDependencyProvider::FACADE_EVENT);
    }
}
