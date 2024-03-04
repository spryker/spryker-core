<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;

interface TaxProductStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\TaxProductStorageTransfer>
     */
    public function getTaxProductTransfersFromProductAbstractsByIds(array $productAbstractIds): array;

    /**
     * @deprecated Use {@link getSynchronizationDataTransfersFromTaxProductStorages()} instead.
     *
     * @param array<int> $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(array $productAbstractIds, ?string $keyColumn = null): array;

    /**
     * @param array<int> $productAbstractIds
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersFromTaxProductStorages(
        array $productAbstractIds,
        ?FilterTransfer $filterTransfer = null
    ): array;

    /**
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getAllSynchronizationDataTransfersFromTaxProductStorages(): array;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer;
}
