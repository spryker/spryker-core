<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStoragePersistenceFactory getFactory()
 */
class StoreStorageRepository extends AbstractRepository implements StoreStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreStorageSynchronizationDataTransfers(StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer): array
    {
        $storeStorageQuery = $this->getFactory()->createStoreStorageQuery();

        $storeStorageConditionsTransfer = $storeStorageCriteriaTransfer->getStoreStorageConditions();
        if ($storeStorageConditionsTransfer && $storeStorageConditionsTransfer->getStoreIds()) {
            $storeStorageQuery->filterByFkStore_In($storeStorageConditionsTransfer->getStoreIds());
        }

        $paginationTransfer = $storeStorageCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $storeStorageQuery = $this->preparePagination($storeStorageQuery, $paginationTransfer);
        }

        $storeStorageEntities = $storeStorageQuery->find();

        $synchronizationDataTransfers = [];
        foreach ($storeStorageEntities as $storeStorageEntity) {
            /** @var string $data */
            $data = $storeStorageEntity->getData();

            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($storeStorageEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $storeListStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreListStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $storeListStorageIds): array
    {
        $storeListStorageQuery = $this->getFactory()->createStoreListStorageQuery();
        if ($storeListStorageIds) {
            $storeListStorageQuery->filterByIdStoreListStorage_In($storeListStorageIds);
        }

        return $this->buildQueryFromCriteria($storeListStorageQuery, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function preparePagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getOffset() || $paginationTransfer->getLimit()) {
            $query->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $query;
        }

        $paginationModel = $query->paginate(
            $paginationTransfer->getPageOrFail(),
            $paginationTransfer->getMaxPerPageOrFail(),
        );

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }
}
