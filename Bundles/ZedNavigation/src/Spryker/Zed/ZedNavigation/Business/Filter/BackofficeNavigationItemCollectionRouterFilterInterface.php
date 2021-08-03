<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Filter;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;

interface BackofficeNavigationItemCollectionRouterFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterNavigationItemCollectionByRouteAccessibility(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer;
}
