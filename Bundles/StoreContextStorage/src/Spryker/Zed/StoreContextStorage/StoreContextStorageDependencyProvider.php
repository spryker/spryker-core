<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeBridge;

/**
 * @method \Spryker\Zed\StoreContextStorage\StoreContextStorageConfig getConfig()
 */
class StoreContextStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_STORE_STORAGE = 'FACADE_STORE_STORAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStoreStorageFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new StoreContextStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreStorageFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE_STORAGE, function (Container $container) {
            return new StoreContextStorageToStoreStorageFacadeBridge(
                $container->getLocator()->storeStorage()->facade(),
            );
        });

        return $container;
    }
}
