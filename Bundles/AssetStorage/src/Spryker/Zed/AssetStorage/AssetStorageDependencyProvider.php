<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage;

use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeBridge;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetFacadeInterface;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeBridge;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToStoreFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 */
class AssetStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_ASSET = 'FACADE_ASSET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);

        $this->addFacadeStore($container);
        $this->addAssetFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addAssetFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeStore(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container): AssetStorageToStoreFacadeInterface {
            return new AssetStorageToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addAssetFacade(Container $container): Container
    {
        $container->set(static::FACADE_ASSET, function (Container $container): AssetStorageToAssetFacadeInterface {
            return new AssetStorageToAssetFacadeBridge($container->getLocator()->asset()->facade());
        });

        return $container;
    }
}
