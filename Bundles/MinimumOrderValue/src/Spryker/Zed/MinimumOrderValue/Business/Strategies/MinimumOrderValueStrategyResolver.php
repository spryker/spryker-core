<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategies;

use Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException;

class MinimumOrderValueStrategyResolver implements MinimumOrderValueStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[]
     */
    protected $minimumOrderValueStrategies;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[] $minimumOrderValueStrategies
     */
    public function __construct(
        array $minimumOrderValueStrategies
    ) {
        $this->minimumOrderValueStrategies = $minimumOrderValueStrategies;
    }

    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface
     */
    public function resolveMinimumOrderValueStrategy(string $strategyKey): MinimumOrderValueStrategyInterface
    {
        foreach ($this->minimumOrderValueStrategies as $minimumOrderValueStrategy) {
            if ($minimumOrderValueStrategy->getKey() === $strategyKey) {
                return $minimumOrderValueStrategy;
            }
        }

        throw new StrategyNotFoundException($strategyKey);
    }
}
