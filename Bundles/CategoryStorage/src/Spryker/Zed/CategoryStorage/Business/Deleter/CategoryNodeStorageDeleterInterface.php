<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter;

interface CategoryNodeStorageDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $nodeTransfers, array $categoryNodeIds): void;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCollection(array $categoryNodeIds): void;
}
