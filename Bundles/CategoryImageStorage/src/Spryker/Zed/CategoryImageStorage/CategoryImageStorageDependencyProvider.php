<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToEventBehaviorBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryImageStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    public const PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE = 'PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE';
    public const PROPEL_QUERY_CATEGORY_IMAGE_SET = 'PROPEL_QUERY_CATEGORY_IMAGE_SET';

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
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::PROPEL_QUERY_CATEGORY_IMAGE_SET] = function () {
            return SpyCategoryImageSetQuery::create();
        };

        $container[static::PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE] = function () {
            return SpyCategoryImageSetToCategoryImageQuery::create();
        };

        return $container;
    }
}
