<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productLabelIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductLabelIds(array $productLabelIds): array;

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

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractLabelStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productAbstractLabelStorageIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productLabelDictionaryStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productLabelDictionaryStorageIds
    ): array;
}
