<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder;

use Spryker\Zed\FactFinder\Dependency\Facade\FactFinderToLocaleBridge;
use Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToCategoryDataFeedBridge;
use Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToProductAbstractDataFeedBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FactFinderDependencyProvider extends AbstractBundleDependencyProvider
{

    const PRODUCT_ABSTRACT_DATA_FEED = 'PRODUCT_ABSTRACT_DATA_FEED';
    const CATEGORY_DATA_FEED = 'CATEGORY_DATA_FEED';
    const LOCALE_FACADE = 'LOCALE_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::LOCALE_FACADE] = function (Container $container) {
            $localeFacade = $container->getLocator()
                ->locale()
                ->facade();

            return new FactFinderToLocaleBridge($localeFacade);
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
        $container[self::PRODUCT_ABSTRACT_DATA_FEED] = function (Container $container) {
            $productAbstractDataFeedQueryContainer = $container->getLocator()
                ->productAbstractDataFeed()
                ->queryContainer();

            return new FactFinderToProductAbstractDataFeedBridge($productAbstractDataFeedQueryContainer);
        };

        $container[self::CATEGORY_DATA_FEED] = function (Container $container) {
            $categoryDataFeedQueryContainer = $container->getLocator()
                ->categoryDataFeed()
                ->queryContainer();

            return new FactFinderToCategoryDataFeedBridge($categoryDataFeedQueryContainer);
        };

        return $container;
    }

}
