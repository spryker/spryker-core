<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\TreeBuilder;

interface CategoryStorageNodeTreeBuilderInterface
{
    /**
     * @param array<int> $categoryNodeIds
     * @param array<\Generated\Shared\Transfer\NodeTransfer> $nodeTransfers
     *
     * @return array<array<array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>>>
     */
    public function buildCategoryNodeStorageTransferTreesForLocaleAndStore(array $categoryNodeIds, array $nodeTransfers): array;
}
