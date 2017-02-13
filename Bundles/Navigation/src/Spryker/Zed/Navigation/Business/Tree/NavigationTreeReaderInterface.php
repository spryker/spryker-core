<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationTransfer;

interface NavigationTreeReaderInterface
{

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer);

}
