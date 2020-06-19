<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

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
     * @param array $navigationDefinitionData
     * @param array $rootDefinitionData
     *
     * @return array
     */
    public function mergeNavigation(array $navigationDefinitionData, array $rootDefinitionData): array
    {
        return $navigationDefinitionData;
    }
}
