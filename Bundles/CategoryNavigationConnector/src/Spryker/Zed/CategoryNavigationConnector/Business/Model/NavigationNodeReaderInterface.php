<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

interface NavigationNodeReaderInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function getNavigationNodesFromCategoryNodeId($idCategoryNode);
}
