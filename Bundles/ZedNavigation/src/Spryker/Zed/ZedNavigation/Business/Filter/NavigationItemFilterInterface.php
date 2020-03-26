<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Filter;

interface NavigationItemFilterInterface
{
    /**
     * @param array $navigationItems
     *
     * @return array
     */
    public function filterNavigationItems(array $navigationItems): array;
}
