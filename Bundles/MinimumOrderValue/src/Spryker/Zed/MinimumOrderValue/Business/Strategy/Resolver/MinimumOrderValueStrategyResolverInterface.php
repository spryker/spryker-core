<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver;

use Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface;

interface MinimumOrderValueStrategyResolverInterface
{
    /**
     * @param string $minimumOrderValueTypeKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface
     */
    public function resolveMinimumOrderValueStrategy(string $minimumOrderValueTypeKey): MinimumOrderValueStrategyPluginInterface;
}
