<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

use Laminas\Config\Config;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class NavigationFullMergeStrategy implements NavigationMergeStrategyInterface
{
    /**
     * @return string
     */
    public function getMergeStrategy(): string
    {
        return ZedNavigationConfig::FULL_MERGE_STRATEGY;
    }

    /**
     * @param \Laminas\Config\Config $navigationDefinition
     * @param \Laminas\Config\Config $rootDefinition
     * @param \Laminas\Config\Config $coreNavigationDefinition
     *
     * @return array
     */
    public function mergeNavigation(Config $navigationDefinition, Config $rootDefinition, Config $coreNavigationDefinition): array
    {
        return $navigationDefinition->merge($rootDefinition)->toArray();
    }
}
