<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeBridge;

class MinimumOrderValueDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES = 'MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES';
    public const FACADE_MESSENGER = 'MESSENGER_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMinimumOrderValueDataSourceStrategies($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMinimumOrderValueDataSourceStrategies(Container $container): Container
    {
        $container[self::MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES] = function (Container $container) {
            return $this->getMinimumOrderValueDataSourceStrategies($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new MinimumOrderValueToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected function getMinimumOrderValueDataSourceStrategies(Container $container): array
    {
        return [];
    }
}
