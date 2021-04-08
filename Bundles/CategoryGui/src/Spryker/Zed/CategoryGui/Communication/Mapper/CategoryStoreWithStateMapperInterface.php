<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Mapper;

use Generated\Shared\Transfer\StoreWithStateCollectionTransfer;

interface CategoryStoreWithStateMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer[] $categoryStoreRelatedTransfers
     *
     * @return \Generated\Shared\Transfer\StoreWithStateCollectionTransfer
     */
    public function mapStoresWithCategoryStoreRelatedTransfersToStoreWithStateCollection(
        array $storeTransfers,
        array $categoryStoreRelatedTransfers
    ): StoreWithStateCollectionTransfer;
}
