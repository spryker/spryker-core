<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypePersistenceFactory getFactory()
 */
class ShipmentTypeRepository extends AbstractRepository implements ShipmentTypeRepositoryInterface
{
    /**
     * @module Store
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer {
        $shipmentTypeQuery = $this->getFactory()->createShipmentTypeQuery();
        $shipmentTypeQuery = $this->applyShipmentTypeFilters($shipmentTypeQuery, $shipmentTypeCriteriaTransfer);
        $shipmentTypeQuery = $this->applyShipmentTypeSorting($shipmentTypeQuery, $shipmentTypeCriteriaTransfer);

        $shipmentTypeCollectionTransfer = new ShipmentTypeCollectionTransfer();
        $paginationTransfer = $shipmentTypeCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $shipmentTypeQuery = $this->applyShipmentTypePagination($shipmentTypeQuery, $paginationTransfer);
            $shipmentTypeCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createShipmentTypeMapper()
            ->mapShipmentTypeEntityCollectionToShipmentTypeCollectionTransfer(
                $shipmentTypeQuery->find(),
                $shipmentTypeCollectionTransfer,
            );
    }

    /**
     * @module Store
     *
     * @param list<int> $shipmentTypeIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function getShipmentTypeStoreRelationsIndexedByIdShipmentType(array $shipmentTypeIds): array
    {
        $shipmentTypeStoreEntities = $this->getFactory()->createShipmentTypeStoreQuery()
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->joinWithStore()
            ->find();

        return $this->getFactory()
            ->createShipmentTypeMapper()
            ->mapShipmentTypeStoreEntitiesToStoreRelationTransfersIndexedByIdShipmentStore($shipmentTypeStoreEntities, []);
    }

    /**
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery $shipmentTypeQuery
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function applyShipmentTypeFilters(
        SpyShipmentTypeQuery $shipmentTypeQuery,
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): SpyShipmentTypeQuery {
        $shipmentTypeConditionsTransfer = $shipmentTypeCriteriaTransfer->getShipmentTypeConditions();
        if ($shipmentTypeConditionsTransfer === null) {
            return $shipmentTypeQuery;
        }

        if ($shipmentTypeConditionsTransfer->getShipmentTypeIds() !== []) {
            $shipmentTypeQuery->filterByIdShipmentType_In($shipmentTypeConditionsTransfer->getShipmentTypeIds());
        }

        if ($shipmentTypeConditionsTransfer->getUuids() !== []) {
            $shipmentTypeQuery->filterByUuid_In($shipmentTypeConditionsTransfer->getUuids());
        }

        if ($shipmentTypeConditionsTransfer->getKeys() !== []) {
            $shipmentTypeQuery->filterByKey_In($shipmentTypeConditionsTransfer->getKeys());
        }

        if ($shipmentTypeConditionsTransfer->getNames() !== []) {
            $shipmentTypeQuery->filterByName_In($shipmentTypeConditionsTransfer->getNames());
        }

        if ($shipmentTypeConditionsTransfer->getStoreNames() !== []) {
            $shipmentTypeQuery
                ->groupByIdShipmentType()
                ->useShipmentTypeStoreQuery()
                    ->useStoreQuery()
                        ->filterByName_In($shipmentTypeConditionsTransfer->getStoreNames())
                    ->endUse()
                ->endUse();
        }

        if ($shipmentTypeConditionsTransfer->getIsActive() !== null) {
            $shipmentTypeQuery->filterByIsActive($shipmentTypeConditionsTransfer->getIsActiveOrFail());
        }

        return $shipmentTypeQuery;
    }

    /**
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery $shipmentTypeQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyShipmentTypePagination(
        SpyShipmentTypeQuery $shipmentTypeQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($shipmentTypeQuery->count());

            $shipmentTypeQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $shipmentTypeQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $paginationModel = $shipmentTypeQuery->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer
                ->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getQuery();
        }

        return $shipmentTypeQuery;
    }

    /**
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery $shipmentTypeQuery
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function applyShipmentTypeSorting(
        SpyShipmentTypeQuery $shipmentTypeQuery,
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): SpyShipmentTypeQuery {
        $sortCollection = $shipmentTypeCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $shipmentTypeQuery->orderBy($sortTransfer->getFieldOrFail(), $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $shipmentTypeQuery;
    }
}
