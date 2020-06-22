<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

use Zend\Config\Config;

interface NavigationMergeStrategyInterface
{
    /**
     * @return string
     */
    public function getMergeStrategy(): string;

    /**
     * @param \Zend\Config\Config $navigationDefinition
     * @param \Zend\Config\Config $rootDefinition
     * @param array $coreNavigationDefinitionData
     *
     * @return array
     */
    public function mergeNavigation(Config $navigationDefinition, Config $rootDefinition, array $coreNavigationDefinitionData): array;
}
