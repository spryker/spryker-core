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
     * @param array<int> $productLabelIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductLabelIds(array $productLabelIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer>
     */
    public function getProductAbstractLabelStorageTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer>
     */
    public function getProductLabelDictionaryStorageTransfers(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productAbstractLabelStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productAbstractLabelStorageIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productLabelDictionaryStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productLabelDictionaryStorageIds
    ): array;
}
