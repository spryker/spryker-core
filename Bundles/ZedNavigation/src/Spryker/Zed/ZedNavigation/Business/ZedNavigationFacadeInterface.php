<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business;

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory getFactory()
 */
interface ZedNavigationFacadeInterface
{
    /**
     * Specification:
     * - Returns an array with all navigation entries.
     * - Calls a stack of `NavigationItemFilterPluginInterface` to filter navigation items.
     * - When navigation cache is enabled it returns cached navigation.
     *
     * @api
     *
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo);

    /**
     * Specification:
     * - Writes generated navigation to a cache file.
     * - This file is used to return navigation in `buildNavigation` when cache is enabled.
     *
     * @api
     *
     * @return void
     */
    public function writeNavigationCache();

    /**
     * Specification:
     * - Removes the navigation cache file.
     *
     * @api
     *
     * @return void
     */
    public function removeNavigationCache(): void;
}
