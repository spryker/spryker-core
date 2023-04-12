<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
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
            $servicePointQuery = $this->applyServicePointPagination($servicePointQuery, $paginationTransfer);
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
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery $servicePointQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyServicePointPagination(
        SpyServicePointQuery $servicePointQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($servicePointQuery->count());

            $servicePointQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $servicePointQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $servicePointQuery->paginate(
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

        return $servicePointQuery;
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
}
