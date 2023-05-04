<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointTableMap;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointPersistenceFactory getFactory()
 */
class ServicePointRepository extends AbstractRepository implements ServicePointRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer {
        $servicePointQuery = $this->getFactory()->getServicePointQuery();

        $servicePointQuery = $this->applyServicePointFilters($servicePointQuery, $servicePointCriteriaTransfer);
        $servicePointQuery = $this->applyServicePointSorting($servicePointQuery, $servicePointCriteriaTransfer);

        $servicePointCollectionTransfer = new ServicePointCollectionTransfer();
        $paginationTransfer = $servicePointCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $servicePointQuery = $this->applyModelCriteriaPagination($servicePointQuery, $paginationTransfer);
            $servicePointCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointEntitiesToServicePointCollectionTransfer(
                $servicePointQuery->find(),
                $servicePointCollectionTransfer,
            );
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return array<string, int>
     */
    public function getServicePointIdsIndexedByServicePointUuid(array $servicePointUuids): array
    {
        return $this->getFactory()->getServicePointQuery()
            ->select([SpyServicePointTableMap::COL_ID_SERVICE_POINT, SpyServicePointTableMap::COL_UUID])
            ->filterByUuid_In($servicePointUuids)
            ->find()
            ->toKeyValue(SpyServicePointTableMap::COL_UUID, SpyServicePointTableMap::COL_ID_SERVICE_POINT);
    }

    /**
     * @module Store
     *
     * @param list<int> $servicePointIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\StoreTransfer>>
     */
    public function getServicePointStoresGroupedByIdServicePoint(array $servicePointIds): array
    {
        $servicePointStoreEntities = $this->getFactory()->getServicePointStoreQuery()
            ->filterByFkServicePoint_In($servicePointIds)
            ->joinWithStore()
            ->find();

        return $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointStoreEntitiesToStoreTransfers($servicePointStoreEntities);
    }

    /**
     * @module Country
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function getServicePointAddressCollection(
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): ServicePointAddressCollectionTransfer {
        $servicePointAddressQuery = $this->getFactory()->getServicePointAddressQuery()
            ->joinWithCountry()
            ->joinWithServicePoint()
            ->leftJoinWithRegion();

        $servicePointAddressQuery = $this->applyServicePointAddressFilters($servicePointAddressQuery, $servicePointAddressCriteriaTransfer);
        $servicePointAddressQuery = $this->applyServicePointAddressSorting($servicePointAddressQuery, $servicePointAddressCriteriaTransfer);

        $servicePointAddressCollectionTransfer = new ServicePointAddressCollectionTransfer();
        $paginationTransfer = $servicePointAddressCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $servicePointAddressQuery = $this->applyModelCriteriaPagination($servicePointAddressQuery, $paginationTransfer);
            $servicePointAddressCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createServicePointAddressMapper()
            ->mapServicePointAddressEntitiesToServicePointAddressCollectionTransfer(
                $servicePointAddressQuery->find(),
                $servicePointAddressCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery $servicePointQuery
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function applyServicePointFilters(
        SpyServicePointQuery $servicePointQuery,
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): SpyServicePointQuery {
        $servicePointConditionsTransfer = $servicePointCriteriaTransfer->getServicePointConditions();

        if (!$servicePointConditionsTransfer) {
            return $servicePointQuery;
        }

        if ($servicePointConditionsTransfer->getKeys()) {
            $servicePointQuery->filterByKey_In($servicePointConditionsTransfer->getKeys());
        }

        if ($servicePointConditionsTransfer->getUuids()) {
            $servicePointQuery->filterByUuid(
                $servicePointConditionsTransfer->getUuids(),
                $servicePointConditionsTransfer->getIsUuidsConditionInversed() ? Criteria::NOT_IN : Criteria::IN,
            );
        }

        return $servicePointQuery;
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery $servicePointAddressQuery
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery
     */
    protected function applyServicePointAddressFilters(
        SpyServicePointAddressQuery $servicePointAddressQuery,
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): SpyServicePointAddressQuery {
        $servicePointAddressConditionsTransfer = $servicePointAddressCriteriaTransfer->getServicePointAddressConditions();

        if (!$servicePointAddressConditionsTransfer) {
            return $servicePointAddressQuery;
        }

        if ($servicePointAddressConditionsTransfer->getServicePointUuids()) {
            $servicePointAddressQuery
                ->useServicePointQuery()
                    ->filterByUuid_In($servicePointAddressConditionsTransfer->getServicePointUuids())
                ->endUse();
        }

        if ($servicePointAddressConditionsTransfer->getUuids()) {
            $servicePointAddressQuery->filterByUuid_In($servicePointAddressConditionsTransfer->getUuids());
        }

        return $servicePointAddressQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyModelCriteriaPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $modelCriteria;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPage(),
                $paginationTransfer->getMaxPerPage(),
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

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery $servicePointQuery
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function applyServicePointSorting(
        SpyServicePointQuery $servicePointQuery,
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): SpyServicePointQuery {
        $sortCollection = $servicePointCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $servicePointQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $servicePointQuery;
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery $servicePointAddressQuery
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery
     */
    protected function applyServicePointAddressSorting(
        SpyServicePointAddressQuery $servicePointAddressQuery,
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): SpyServicePointAddressQuery {
        $sortCollection = $servicePointAddressCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $servicePointAddressQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $servicePointAddressQuery;
    }
}
