<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory getFactory()
 */
interface ZedNavigationFacadeInterface
{
    /**
     * Specification:
     * - Returns an array with all navigation entries.
     * - Calls a stack of `NavigationItemCollectionFilterPluginInterface` to filter navigation items.
     * - Calls a stack of `NavigationItemFilterPluginInterface` to filter navigation items.
     * - When navigation cache is enabled it returns cached navigation.
     * - Uses a strategy to define navigation elements merging process.
     *
     * @api
     *
     * @param string $pathInfo
     * @param string|null $navigationType
     *
     * @return array
     */
    public function buildNavigation($pathInfo, ?string $navigationType = null);

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

    /**
     * Specification:
     * - Iterates through the navigation items collection.
     * - Checks if the navigation element has a route.
     * - Returns the navigation items collection without non-existing route items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterNavigationItemCollectionByBackofficeRouteAccessibility(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer;
}
