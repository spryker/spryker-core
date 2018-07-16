<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategies;

interface MinimumOrderValueStrategyResolverInterface
{
    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface
     */
    public function resolveMinimumOrderValueStrategy(string $strategyKey): ?MinimumOrderValueStrategyInterface;
}
