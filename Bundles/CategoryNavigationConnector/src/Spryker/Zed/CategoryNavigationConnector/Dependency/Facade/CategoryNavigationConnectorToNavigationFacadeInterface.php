<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Dependency\Facade;

use Generated\Shared\Transfer\NavigationNodeTransfer;

interface CategoryNavigationConnectorToNavigationFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer);
}
