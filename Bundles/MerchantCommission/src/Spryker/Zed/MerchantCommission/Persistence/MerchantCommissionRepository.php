<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Persistence;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionPersistenceFactory getFactory()
 */
class MerchantCommissionRepository extends AbstractRepository implements MerchantCommissionRepositoryInterface
{
    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionStoreTableMap::COL_FK_STORE
     *
     * @var string
     */
    protected const COL_FK_STORE = 'spy_merchant_commission_store.fk_store';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionMerchantTableMap::COL_FK_MERCHANT
     *
     * @var string
     */
    protected const COL_FK_MERCHANT = 'spy_merchant_commission_merchant.fk_merchant';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_KEY
     *
     * @var string
     */
    protected const COL_KEY = 'spy_merchant_commission.key';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @module Merchant
     * @module Store
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer {
        $merchantCommissionCollectionTransfer = new MerchantCommissionCollectionTransfer();
        $merchantCommissionQuery = $this->getFactory()
            ->getMerchantCommissionQuery()
            ->leftJoinWithMerchantCommissionMerchant()
            ->leftJoinWithMerchantCommissionStore()
            ->joinWithMerchantCommissionGroup();

        $merchantCommissionQuery = $this->applyMerchantCommissionFilters(
            $merchantCommissionQuery,
            $merchantCommissionCriteriaTransfer,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $merchantCommissionCriteriaTransfer->getSortCollection();
        $merchantRelationRequestQuery = $this->applySorting($merchantCommissionQuery, $sortTransfers);

        $paginationTransfer = $merchantCommissionCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $merchantCommissionQuery = $this->applyPagination($merchantRelationRequestQuery, $paginationTransfer);
            $merchantCommissionCollectionTransfer->setPagination($paginationTransfer);
        }

        $merchantCommissionEntities = $merchantCommissionQuery->find();
        if ($merchantCommissionEntities->count() === 0) {
            return $merchantCommissionCollectionTransfer;
        }

        return $this->getFactory()
            ->createMerchantCommissionMapper()
            ->mapMerchantCommissionEntitiesToMerchantCommissionCollectionTransfer(
                $merchantCommissionEntities,
                $merchantCommissionCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer
     */
    public function getMerchantCommissionAmountCollection(
        MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
    ): MerchantCommissionAmountCollectionTransfer {
        $merchantCommissionAmountCollectionTransfer = new MerchantCommissionAmountCollectionTransfer();
        $merchantCommissionAmountQuery = $this->getFactory()
            ->getMerchantCommissionAmountQuery()
            ->joinWithCurrency()
            ->joinWithMerchantCommission();

        $merchantCommissionAmountQuery = $this->applyMerchantCommissionAmountFilters(
            $merchantCommissionAmountQuery,
            $merchantCommissionAmountCriteriaTransfer,
        );

        /** @var \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount> $merchantCommissionAmountEntities */
        $merchantCommissionAmountEntities = $merchantCommissionAmountQuery->find();
        if ($merchantCommissionAmountEntities->count() === 0) {
            return $merchantCommissionAmountCollectionTransfer;
        }

        return $this->getFactory()
            ->createMerchantCommissionMapper()
            ->mapMerchantCommissionAmountEntitiesToMerchantCommissionAmountCollectionTransfer(
                $merchantCommissionAmountEntities,
                $merchantCommissionAmountCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollection(
        MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
    ): MerchantCommissionGroupCollectionTransfer {
        $merchantCommissionGroupCollectionTransfer = new MerchantCommissionGroupCollectionTransfer();
        $merchantCommissionGroupQuery = $this->getFactory()->getMerchantCommissionGroupQuery();
        $merchantCommissionGroupQuery = $this->applyMerchantCommissionGroupFilters(
            $merchantCommissionGroupQuery,
            $merchantCommissionGroupCriteriaTransfer,
        );

        /** @var \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroup> $merchantCommissionGroupEntities */
        $merchantCommissionGroupEntities = $merchantCommissionGroupQuery->find();
        if ($merchantCommissionGroupEntities->count() === 0) {
            return $merchantCommissionGroupCollectionTransfer;
        }

        return $this->getFactory()
            ->createMerchantCommissionMapper()
            ->mapMerchantCommissionGroupEntitiesToMerchantCommissionGroupCollectionTransfer(
                $merchantCommissionGroupEntities,
                $merchantCommissionGroupCollectionTransfer,
            );
    }

    /**
     * @param int $idMerchantCommission
     *
     * @return list<int>
     */
    public function getStoreIdsRelatedToMerchantCommission(int $idMerchantCommission): array
    {
        return $this->getFactory()
            ->getMerchantCommissionStoreQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->select([static::COL_FK_STORE])
            ->find()
            ->getData();
    }

    /**
     * @param int $idMerchantCommission
     *
     * @return list<int>
     */
    public function getMerchantIdsRelatedToMerchantCommission(int $idMerchantCommission): array
    {
        return $this->getFactory()
            ->getMerchantCommissionMerchantQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->select([static::COL_FK_MERCHANT])
            ->find()
            ->getData();
    }

    /**
     * @param list<string> $merchantCommissionKeys
     *
     * @return list<string>
     */
    public function getExistingMerchantCommissionKeys(array $merchantCommissionKeys): array
    {
        return $this->getFactory()
            ->getMerchantCommissionQuery()
            ->filterByKey_In($merchantCommissionKeys)
            ->select([static::COL_KEY])
            ->find()
            ->getData();
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery $merchantCommissionQuery
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function applyMerchantCommissionFilters(
        SpyMerchantCommissionQuery $merchantCommissionQuery,
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): SpyMerchantCommissionQuery {
        $merchantCommissionConditionsTransfer = $merchantCommissionCriteriaTransfer->getMerchantCommissionConditions();
        if (!$merchantCommissionConditionsTransfer) {
            return $merchantCommissionQuery;
        }

        if ($merchantCommissionConditionsTransfer->getMerchantCommissionIds() !== []) {
            $merchantCommissionQuery->filterByIdMerchantCommission_In(
                $merchantCommissionConditionsTransfer->getMerchantCommissionIds(),
            );
        }

        if ($merchantCommissionConditionsTransfer->getUuids() !== []) {
            $merchantCommissionQuery->filterByUuid_In(
                $merchantCommissionConditionsTransfer->getUuids(),
            );
        }

        if ($merchantCommissionConditionsTransfer->getKeys() !== []) {
            $merchantCommissionQuery->filterByKey_In(
                $merchantCommissionConditionsTransfer->getKeys(),
            );
        }

        if ($merchantCommissionConditionsTransfer->getStoreNames() !== []) {
            $merchantCommissionQuery
                ->useMerchantCommissionStoreQuery()
                    ->useStoreQuery()
                        ->filterByName_In($merchantCommissionConditionsTransfer->getStoreNames())
                    ->endUse()
                ->endUse();
        }

        if ($merchantCommissionConditionsTransfer->getMerchantIds() !== []) {
            $merchantCommissionQuery
                ->useMerchantCommissionMerchantQuery()
                    ->useMerchantQuery()
                        ->filterByIdMerchant_In($merchantCommissionConditionsTransfer->getMerchantIds())
                    ->endUse()
                ->endUse();
        }

        if ($merchantCommissionConditionsTransfer->getMerchantCommissionGroupNames() !== []) {
            $merchantCommissionQuery
                ->useMerchantCommissionGroupQuery()
                    ->filterByName_In($merchantCommissionConditionsTransfer->getMerchantCommissionGroupNames())
                ->endUse();
        }

        if ($merchantCommissionConditionsTransfer->getIsActive() !== null) {
            $merchantCommissionQuery->filterByIsActive(
                $merchantCommissionConditionsTransfer->getIsActiveOrFail(),
            );
        }

        if ($merchantCommissionConditionsTransfer->getWithinValidityDateRange() !== null) {
            $dateTimeFormatted = (new DateTime())->format(static::DATE_TIME_FORMAT);

            $whereClause = $merchantCommissionConditionsTransfer->getWithinValidityDateRange()
                ? sprintf('(%s <= ? AND %s >= ?)', SpyMerchantCommissionTableMap::COL_VALID_FROM, SpyMerchantCommissionTableMap::COL_VALID_TO)
                : sprintf('(%s > ? OR %s < ?)', SpyMerchantCommissionTableMap::COL_VALID_FROM, SpyMerchantCommissionTableMap::COL_VALID_TO);
            $merchantCommissionQuery
                ->where($whereClause, [$dateTimeFormatted, $dateTimeFormatted])
                ->_or()
                ->where(
                    sprintf('(%s IS NULL AND %s IS NULL )', SpyMerchantCommissionTableMap::COL_VALID_FROM, SpyMerchantCommissionTableMap::COL_VALID_TO),
                );
        }

        return $merchantCommissionQuery;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery $merchantCommissionAmountQuery
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery
     */
    protected function applyMerchantCommissionAmountFilters(
        SpyMerchantCommissionAmountQuery $merchantCommissionAmountQuery,
        MerchantCommissionAmountCriteriaTransfer $merchantCommissionAmountCriteriaTransfer
    ): SpyMerchantCommissionAmountQuery {
        $merchantCommissionAmountConditionsTransfer = $merchantCommissionAmountCriteriaTransfer->getMerchantCommissionAmountConditions();
        if (!$merchantCommissionAmountConditionsTransfer) {
            return $merchantCommissionAmountQuery;
        }

        if ($merchantCommissionAmountConditionsTransfer->getMerchantCommissionIds() !== []) {
            $merchantCommissionAmountQuery->filterByFkMerchantCommission_In(
                $merchantCommissionAmountConditionsTransfer->getMerchantCommissionIds(),
            );
        }

        return $merchantCommissionAmountQuery;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery $merchantCommissionGroupQuery
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery
     */
    protected function applyMerchantCommissionGroupFilters(
        SpyMerchantCommissionGroupQuery $merchantCommissionGroupQuery,
        MerchantCommissionGroupCriteriaTransfer $merchantCommissionGroupCriteriaTransfer
    ): SpyMerchantCommissionGroupQuery {
        $merchantCommissionGroupConditionsTransfer = $merchantCommissionGroupCriteriaTransfer->getMerchantCommissionGroupConditions();
        if (!$merchantCommissionGroupConditionsTransfer) {
            return $merchantCommissionGroupQuery;
        }

        if ($merchantCommissionGroupConditionsTransfer->getUuids() !== []) {
            $merchantCommissionGroupQuery->filterByUuid_In(
                $merchantCommissionGroupConditionsTransfer->getUuids(),
            );
        }

        if ($merchantCommissionGroupConditionsTransfer->getKeys() !== []) {
            $merchantCommissionGroupQuery->filterByKey_In(
                $merchantCommissionGroupConditionsTransfer->getKeys(),
            );
        }

        return $merchantCommissionGroupQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }
}
