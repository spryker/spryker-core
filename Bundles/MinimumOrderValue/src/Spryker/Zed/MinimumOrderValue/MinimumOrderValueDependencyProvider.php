<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Strategy\HardThresholdStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Strategy\SoftThresholdWithFixedFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Strategy\SoftThresholdWithFlexibleFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\Strategy\SoftThresholdWithMessageStrategy;

class MinimumOrderValueDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MINIMUM_ORDER_VALUE_STRATEGIES = 'MINIMUM_ORDER_VALUE_STRATEGIES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::MINIMUM_ORDER_VALUE_STRATEGIES] = function (Container $container) {
            return $this->getMinimumOrderValueStrategies($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[]
     */
    protected function getMinimumOrderValueStrategies(Container $container): array
    {
        return [
            new HardThresholdStrategy(),
            new SoftThresholdWithMessageStrategy(),
            new SoftThresholdWithFixedFeeStrategy(),
            new SoftThresholdWithFlexibleFeeStrategy(),
        ];
    }
}
