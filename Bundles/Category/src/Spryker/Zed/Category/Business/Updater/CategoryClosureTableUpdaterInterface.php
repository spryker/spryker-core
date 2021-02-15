<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\NodeTransfer;

interface CategoryClosureTableUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function updateCategoryClosureTableParentEntriesForCategoryNode(NodeTransfer $nodeTransfer): void;
}
