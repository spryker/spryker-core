<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeBridge;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_CONFIGURATION = 'FACADE_PRODUCT_CONFIGURATION';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addFacadeProductConfiguration($container);
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addFacadeProductConfiguration(Container $container): void
    {
        $container->set(static::FACADE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationStorageToProductConfigurationFacadeBridge(
                $container->getLocator()->productConfiguration()->facade()
            );
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductConfigurationStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }
}
