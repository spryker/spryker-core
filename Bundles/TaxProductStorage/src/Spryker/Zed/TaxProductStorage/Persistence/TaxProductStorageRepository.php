<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 */
class TaxProductStorageRepository extends AbstractRepository implements TaxProductStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\TaxProductStorageTransfer>
     */
    public function getTaxProductTransfersFromProductAbstractsByIds(array $productAbstractIds): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $spyProductAbstracts = $this->getFactory()
            ->getProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find()
            ->getArrayCopy();

        if (count($spyProductAbstracts) === 0) {
            return [];
        }

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyProductAbstractsToTaxProductStorageTransfers($spyProductAbstracts);
    }

    /**
     * @param array<int> $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(
        array $productAbstractIds,
        ?string $keyColumn = null
    ): array {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $spyTaxProductStorages = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyTaxProductStorageToSynchronizationDataTransfer($spyTaxProductStorages);
    }

    /**
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getAllSynchronizationDataTransfersFromTaxProductStorages(): array
    {
        $spyTaxProductStorages = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyTaxProductStorageToSynchronizationDataTransfer($spyTaxProductStorages);
    }

    /**
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer
    {
        $productAbstractCollectionTransfer = new ProductAbstractCollectionTransfer();
        $productAbstractQuery = $this->getFactory()->getProductAbstractQuery();

        $paginationTransfer = $productAbstractCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $productAbstractQuery = $this->applyProductAbstractPagination($productAbstractQuery, $paginationTransfer);
            $productAbstractCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapProductAbstractEntitiesToProductAbstractCollectionTransfer(
                $productAbstractQuery->find(),
                $productAbstractCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function applyProductAbstractPagination(
        SpyProductAbstractQuery $productAbstractQuery,
        PaginationTransfer $paginationTransfer
    ): SpyProductAbstractQuery {
        $paginationTransfer->setNbResults($productAbstractQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productAbstractQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $productAbstractQuery;
    }
}
