<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

interface NavigationEntityManagerInterface
{
    /**
     * @param int[] $duplicatedNavigationNodeIdsByNavigationNodeIds
     *
     * @return void
     */
    public function updateFkParentNavigationNodeForDuplicatedNavigationNodes(array $duplicatedNavigationNodeIdsByNavigationNodeIds): void;
}
