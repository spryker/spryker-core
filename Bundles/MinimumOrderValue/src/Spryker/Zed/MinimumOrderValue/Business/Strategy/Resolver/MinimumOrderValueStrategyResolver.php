<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver;

use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException;
use Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface;

class MinimumOrderValueStrategyResolver implements MinimumOrderValueStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[]
     */
    protected $minimumOrderValueStrategyPlugins;

    /**
     * @param \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[] $minimumOrderValueStrategyPlugins
     */
    public function __construct(
        array $minimumOrderValueStrategyPlugins
    ) {
        $this->minimumOrderValueStrategyPlugins = $minimumOrderValueStrategyPlugins;
    }

    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface
     */
    public function resolveMinimumOrderValueStrategy(string $minimumOrderValueTypeKey): MinimumOrderValueStrategyPluginInterface
    {
        foreach ($this->minimumOrderValueStrategyPlugins as $minimumOrderValueStrategy) {
            if ($minimumOrderValueStrategy->getKey() === $minimumOrderValueTypeKey) {
                return $minimumOrderValueStrategy;
            }
        }

        throw new MinimumOrderValueTypeNotFoundException($minimumOrderValueTypeKey);
    }
}
