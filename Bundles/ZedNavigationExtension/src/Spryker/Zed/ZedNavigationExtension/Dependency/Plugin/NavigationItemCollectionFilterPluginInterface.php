<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;

/**
 * Allows to filter visible menu items in navigation.
 * Use this plugin if navigation items need to be filtered before displaying them.
 */
interface NavigationItemCollectionFilterPluginInterface
{
    /**
     * Specification:
     * - Iterates through the navigation items collection.
     * - Removes the navigation item if it cannot be visible in menu.
     * - Returns the filtered navigation items collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filter(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer;
}
