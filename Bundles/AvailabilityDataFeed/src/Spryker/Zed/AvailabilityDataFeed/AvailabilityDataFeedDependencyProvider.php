<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed;

use Spryker\Zed\AvailabilityDataFeed\Dependency\QueryContainer\AvailabilityDataFeedToAvailabilityBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityDataFeedDependencyProvider extends AbstractBundleDependencyProvider
{
    public const AVAILABILITY_QUERY_CONTAINER = 'AVAILABILITY_QUERY_CONTAINER';

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
        $container[self::AVAILABILITY_QUERY_CONTAINER] = function (Container $container) {
            $availabilityQueryContainer = $container->getLocator()
                ->availability()
                ->queryContainer();

            return new AvailabilityDataFeedToAvailabilityBridge($availabilityQueryContainer);
        };

        return $container;
    }
}
