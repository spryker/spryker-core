<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

interface ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer[]
     */
    public function getProductAbstractLabelStorageTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    public function getProductLabelDictionaryStorageTransfers(): array;
}
