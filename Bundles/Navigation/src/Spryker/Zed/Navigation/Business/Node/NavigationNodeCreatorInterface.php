<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeTransfer;

interface NavigationNodeCreatorInterface
{
    /**
     * @param NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return NavigationNodeTransfer
     */
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);
}
