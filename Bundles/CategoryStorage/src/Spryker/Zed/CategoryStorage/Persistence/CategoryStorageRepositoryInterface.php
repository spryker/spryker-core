<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

interface CategoryStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findCategoryNodeStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findCategoryTreeStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function findFilteredCategoryNodeEnteties(int $offset, int $limit): array;
}
