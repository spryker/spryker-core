<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Reader;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;

interface CategoryNodeReaderInterface
{
    /**
     * @param int $idCategory
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
     */
    public function getAllNodesByIdCategory(int $idCategory): array;

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodesWithRelativeNodes(
        CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
    ): NodeCollectionTransfer;
}
