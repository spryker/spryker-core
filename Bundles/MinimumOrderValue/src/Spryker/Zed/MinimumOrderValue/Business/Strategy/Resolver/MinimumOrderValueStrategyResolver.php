<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver;

use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface;

class MinimumOrderValueStrategyResolver implements MinimumOrderValueStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface[]
     */
    protected $minimumOrderValueStrategies;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface[] $minimumOrderValueStrategies
     */
    public function __construct(
        array $minimumOrderValueStrategies
    ) {
        $this->minimumOrderValueStrategies = $minimumOrderValueStrategies;
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface
     */
    public function resolveMinimumOrderValueStrategy(string $minimumOrderValueTypeKey): MinimumOrderValueStrategyInterface
    {
        foreach ($this->minimumOrderValueStrategies as $minimumOrderValueStrategy) {
            if ($minimumOrderValueStrategy->getKey() === $minimumOrderValueTypeKey) {
                return $minimumOrderValueStrategy;
            }
        }

        throw new MinimumOrderValueTypeNotFoundException($minimumOrderValueTypeKey);
    }
}
