<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeTransfer;

interface NavigationNodeDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    public function deleteNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);
}
