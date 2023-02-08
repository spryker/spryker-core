<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Persistence;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStoragePersistenceFactory getFactory()
 */
class CustomerStorageRepository extends AbstractRepository implements CustomerStorageRepositoryInterface
{
    /**
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer {
        $invalidatedCustomerCollectionTransfer = new InvalidatedCustomerCollectionTransfer();
        $customerInvalidatedStorageQuery = $this->applyCustomerInvalidatedStorageFilters(
            $this->getFactory()->createSpyCustomerInvalidatedStorageQuery(),
            $invalidatedCustomerCriteriaTransfer,
        );

        if ($invalidatedCustomerCriteriaTransfer->getPagination()) {
            $customerInvalidatedStorageQuery = $this->applyCustomerInvalidatedStoragePagination(
                $customerInvalidatedStorageQuery,
                $invalidatedCustomerCriteriaTransfer->getPaginationOrFail(),
            );
            $invalidatedCustomerCollectionTransfer->setPagination($invalidatedCustomerCriteriaTransfer->getPagination());
        }

        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorage> $customerInvalidatedStorageEntityCollection
         */
        $customerInvalidatedStorageEntityCollection = $customerInvalidatedStorageQuery->find();

        return $this->getFactory()
            ->createCustomerStorageMapper()
            ->mapCustomerInvalidatedStorageEntityCollectionToInvalidatedCustomerCollectionTransfer(
                $customerInvalidatedStorageEntityCollection,
                $invalidatedCustomerCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getInvalidatedCustomerSynchronizationDataTransferCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): array {
        $customerInvalidatedStorageQuery = $this->applyCustomerInvalidatedStorageFilters(
            $this->getFactory()->createSpyCustomerInvalidatedStorageQuery(),
            $invalidatedCustomerCriteriaTransfer,
        );

        if ($invalidatedCustomerCriteriaTransfer->getPagination()) {
            $customerInvalidatedStorageQuery = $this->applyCustomerInvalidatedStoragePagination(
                $customerInvalidatedStorageQuery,
                $invalidatedCustomerCriteriaTransfer->getPaginationOrFail(),
            );
        }

        /**
         * @var array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer> $synchronizationDataTransferCollection
         */
        $synchronizationDataTransferCollection = $customerInvalidatedStorageQuery
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();

        return $synchronizationDataTransferCollection;
    }

    /**
     * @param \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery $customerInvalidatedStorageQuery
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    protected function applyCustomerInvalidatedStorageFilters(
        SpyCustomerInvalidatedStorageQuery $customerInvalidatedStorageQuery,
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): SpyCustomerInvalidatedStorageQuery {
        $invalidatedCustomerConditionsTransfer = $invalidatedCustomerCriteriaTransfer->getInvalidatedCustomerConditions();

        if (!$invalidatedCustomerConditionsTransfer) {
            return $customerInvalidatedStorageQuery;
        }

        return $this->buildQueryByConditions($invalidatedCustomerConditionsTransfer, $customerInvalidatedStorageQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer $invalidatedCustomerConditionsTransfer
     * @param \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery $invalidatedStorageQuery
     *
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    protected function buildQueryByConditions(
        InvalidatedCustomerConditionsTransfer $invalidatedCustomerConditionsTransfer,
        SpyCustomerInvalidatedStorageQuery $invalidatedStorageQuery
    ): SpyCustomerInvalidatedStorageQuery {
        if ($invalidatedCustomerConditionsTransfer->getCustomerReferences()) {
            $invalidatedStorageQuery->filterByCustomerReference_In($invalidatedCustomerConditionsTransfer->getCustomerReferences());
        }

        return $invalidatedStorageQuery;
    }

    /**
     * @param \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery $invalidatedStorageQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    protected function applyCustomerInvalidatedStoragePagination(
        SpyCustomerInvalidatedStorageQuery $invalidatedStorageQuery,
        PaginationTransfer $paginationTransfer
    ): SpyCustomerInvalidatedStorageQuery {
        if ($paginationTransfer->getOffset() !== null || $paginationTransfer->getLimit() !== null) {
            $invalidatedStorageQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        return $invalidatedStorageQuery;
    }
}
