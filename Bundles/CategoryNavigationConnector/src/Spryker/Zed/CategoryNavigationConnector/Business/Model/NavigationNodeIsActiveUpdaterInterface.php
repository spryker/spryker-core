<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

interface NavigationNodeIsActiveUpdaterInterface
{
    /**
     * @param int $idCategoryNode
     * @param bool $isActive
     *
     * @return void
     */
    public function updateCategoryNodeNavigationNodes($idCategoryNode, $isActive);
}
