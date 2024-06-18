<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;

interface StoreContextRepositoryInterface
{
    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer|null
     */
    public function findStoreApplicationContextCollectionByIdStore(int $idStore): ?StoreApplicationContextCollectionTransfer;

    /**
     * @param array<int> $storeIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer>
     */
    public function getStoreApplicationContextCollectionsIndexedByIdStore(array $storeIds): array;
}
