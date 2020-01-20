<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\NavigationItemTransfer;

interface NavigationItemFilterPluginInterface
{
    /**
     * Specification:
     * - Returns true if navigation item is visible in menu.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemTransfer $navigationItem
     *
     * @return bool
     */
    public function isVisible(NavigationItemTransfer $navigationItem): bool;
}
