<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Navigation;

use Generated\Shared\Transfer\NavigationTransfer;

interface NavigationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigation(NavigationTransfer $navigationTransfer);
}
