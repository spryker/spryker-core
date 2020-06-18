<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

interface NavigationMergeStrategyInterface
{
    /**
     * @return string
     */
    public function getMergeStrategy(): string;

    /**
     * @param array $navigationDefinitionData
     * @param array $rootDefinitionData
     * @param array $secondLevelNavigationData
     *
     * @return array
     */
    public function mergeNavigation(array $navigationDefinitionData, array $rootDefinitionData, array $secondLevelNavigationData): array;
}
