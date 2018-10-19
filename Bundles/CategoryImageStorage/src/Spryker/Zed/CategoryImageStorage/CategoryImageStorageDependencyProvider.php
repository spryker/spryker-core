<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage;

use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageBridge;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToEventBehaviorBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryImageStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_CATEGORY_IMAGE = 'FACADE_CATEGORY_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new CategoryImageStorageToEventBehaviorBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_CATEGORY_IMAGE] = function (Container $container) {
            return new CategoryImageStorageToCategoryImageBridge(
                $container->getLocator()->categoryImage()->facade()
            );
        };

        return $container;
    }
}
