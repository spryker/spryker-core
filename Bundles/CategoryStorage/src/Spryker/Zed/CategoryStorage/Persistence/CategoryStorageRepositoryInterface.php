<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

interface CategoryStorageRepositoryInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeStorageTransfersByCategoryNodeIds(array $categoryNodeIds): array;

    /**
     * @return \Generated\Shared\Transfer\CategoryTreeStorageTransfer[]
     */
    public function getCategoryTreeStorageTransfers(): array;
}
