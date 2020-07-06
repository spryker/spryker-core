<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Resolver;

use Spryker\Zed\ZedNavigation\Business\Exception\MergeStrategyNotFoundException;
use Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class MergeNavigationStrategyResolver implements MergeNavigationStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\ZedNavigation\ZedNavigationConfig
     */
    protected $zedNavigationConfig;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface[]
     */
    protected $navigationMergeStrategies;

    /**
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $zedNavigationConfig
     * @param \Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface[] $navigationMergeStrategies
     */
    public function __construct(ZedNavigationConfig $zedNavigationConfig, array $navigationMergeStrategies)
    {
        $this->zedNavigationConfig = $zedNavigationConfig;
        $this->navigationMergeStrategies = $navigationMergeStrategies;
    }

    /**
     * @throws \Spryker\Zed\ZedNavigation\Business\Exception\MergeStrategyNotFoundException
     *
     * @return \Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface
     */
    public function resolve(): NavigationMergeStrategyInterface
    {
        foreach ($this->navigationMergeStrategies as $navigationMergeStrategy) {
            if ($navigationMergeStrategy->getMergeStrategy() === $this->zedNavigationConfig->getMergeStrategy()) {
                return $navigationMergeStrategy;
            }
        }

        throw new MergeStrategyNotFoundException(sprintf(
            'Merge strategy with name "%s" not found',
            $this->zedNavigationConfig->getMergeStrategy()
        ));
    }
}
