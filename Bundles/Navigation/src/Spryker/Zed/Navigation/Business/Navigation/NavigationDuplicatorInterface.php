<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\DuplicateNavigationTransfer;
use Generated\Shared\Transfer\NavigationResponseTransfer;

interface NavigationDuplicatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DuplicateNavigationTransfer $duplicateNavigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationResponseTransfer
     */
    public function duplicateNavigation(DuplicateNavigationTransfer $duplicateNavigationTransfer): NavigationResponseTransfer;
}
