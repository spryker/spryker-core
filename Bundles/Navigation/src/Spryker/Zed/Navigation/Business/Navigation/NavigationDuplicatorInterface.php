<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationResponseTransfer;
use Generated\Shared\Transfer\NavigationTransfer;

interface NavigationDuplicatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $newNavigationElement
     * @param \Generated\Shared\Transfer\NavigationTransfer $baseNavigationElement
     *
     * @return \Generated\Shared\Transfer\NavigationResponseTransfer
     */
    public function duplicateNavigation(
        NavigationTransfer $newNavigationElement,
        NavigationTransfer $baseNavigationElement
    ): NavigationResponseTransfer;
}
