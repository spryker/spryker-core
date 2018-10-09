<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataFeed;

use Spryker\Zed\CategoryDataFeed\Dependency\QueryContainer\CategoryDataFeedToCategoryBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryDataFeedDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CATEGORY_QUERY_CONTAINER = 'CATEGORY_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::CATEGORY_QUERY_CONTAINER] = function (Container $container) {
            $categoryQueryContainer = $container->getLocator()
                ->category()
                ->queryContainer();

            return new CategoryDataFeedToCategoryBridge($categoryQueryContainer);
        };

        return $container;
    }
}
