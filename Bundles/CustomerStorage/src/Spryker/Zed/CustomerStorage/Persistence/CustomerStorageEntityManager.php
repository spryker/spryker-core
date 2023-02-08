<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Persistence;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\CustomerStorage\CustomerStorageConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStoragePersistenceFactory getFactory()
 */
class CustomerStorageEntityManager extends AbstractEntityManager implements CustomerStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerTransfer $invalidatedCustomerTransfer
     *
     * @return void
     */
    public function saveCustomerInvalidatedStorage(
        InvalidatedCustomerTransfer $invalidatedCustomerTransfer
    ): void {
        $customerInvalidatedStorageData = [
            CustomerStorageConfig::COL_ANONYMIZED_AT => $invalidatedCustomerTransfer->getAnonymizedAt(),
            CustomerStorageConfig::COL_PASSWORD_UPDATED_AT => $invalidatedCustomerTransfer->getPasswordUpdatedAt(),
        ];

        $customerInvalidatedStorageEntity = $this->getFactory()
            ->createSpyCustomerInvalidatedStorageQuery()
            ->filterByCustomerReference($invalidatedCustomerTransfer->getCustomerReference())
            ->findOneOrCreate();

        $customerInvalidatedStorageEntity->setData($customerInvalidatedStorageData)->save();
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionDeleteCriteriaTransfer $invalidatedCustomerCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function deleteInvalidatedCustomerCollection(
        InvalidatedCustomerCollectionDeleteCriteriaTransfer $invalidatedCustomerCollectionDeleteCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer {
        $invalidatedCustomerCollectionTransfer = new InvalidatedCustomerCollectionTransfer();
        $customerInvalidatedStorageQuery = $this->applyCustomerInvalidatedStorageDeleteFilters(
            $this->getFactory()->createSpyCustomerInvalidatedStorageQuery(),
            $invalidatedCustomerCollectionDeleteCriteriaTransfer,
        );

        if ($invalidatedCustomerCollectionDeleteCriteriaTransfer->getPagination()) {
            $invalidatedCustomerCollectionTransfer->setPagination($invalidatedCustomerCollectionDeleteCriteriaTransfer->getPagination());
            $this->applyCustomerInvalidatedStorageDeletePagination($customerInvalidatedStorageQuery, $invalidatedCustomerCollectionDeleteCriteriaTransfer->getPaginationOrFail());
        }

        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorage> $customerInvalidatedStorageEntityCollection
         */
        $customerInvalidatedStorageEntityCollection = $customerInvalidatedStorageQuery->find();
        $customerInvalidatedStorageEntityCollection->delete();

        return $this->getFactory()->createCustomerStorageMapper()
            ->mapCustomerInvalidatedStorageEntityCollectionToInvalidatedCustomerCollectionTransfer(
                $customerInvalidatedStorageEntityCollection,
                $invalidatedCustomerCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery $customerInvalidatedStorageQuery
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionDeleteCriteriaTransfer $invalidatedCustomerCollectionDeleteCriteriaTransfer
     *
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    protected function applyCustomerInvalidatedStorageDeleteFilters(
        SpyCustomerInvalidatedStorageQuery $customerInvalidatedStorageQuery,
        InvalidatedCustomerCollectionDeleteCriteriaTransfer $invalidatedCustomerCollectionDeleteCriteriaTransfer
    ): SpyCustomerInvalidatedStorageQuery {
        $createdAt = $invalidatedCustomerCollectionDeleteCriteriaTransfer->getCreatedAt();
        if ($createdAt !== null) {
            $customerInvalidatedStorageQuery->filterByCreatedAt($createdAt, Criteria::LESS_THAN);
        }

        return $customerInvalidatedStorageQuery;
    }

    /**
     * @param \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery $invalidatedStorageQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\CustomerStorage\Persistence\SpyCustomerInvalidatedStorageQuery
     */
    protected function applyCustomerInvalidatedStorageDeletePagination(
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
