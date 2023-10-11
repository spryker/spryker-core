<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointPersistenceFactory getFactory()
 */
class ShipmentTypeServicePointRepository extends AbstractRepository implements ShipmentTypeServicePointRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function getShipmentTypeServiceTypeCollection(
        ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
    ): ShipmentTypeServiceTypeCollectionTransfer {
        $shipmentTypeServiceTypeQuery = $this->getFactory()
            ->createShipmentTypeServiceTypeQuery();
        $shipmentTypeServiceTypeQuery = $this->applyShipmentTypeServiceTypeFilters(
            $shipmentTypeServiceTypeQuery,
            $shipmentTypeServiceTypeCriteriaTransfer,
        );
        $shipmentTypeServiceTypeQuery = $this->applyShipmentTypeServiceTypeSorting(
            $shipmentTypeServiceTypeQuery,
            $shipmentTypeServiceTypeCriteriaTransfer,
        );
        $shipmentTypeServiceTypeCollectionTransfer = new ShipmentTypeServiceTypeCollectionTransfer();
        $paginationTransfer = $shipmentTypeServiceTypeCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $shipmentTypeServiceTypeQuery = $this->applyShipmentTypeServiceTypePagination($shipmentTypeServiceTypeQuery, $paginationTransfer);
            $shipmentTypeServiceTypeCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createShipmentTypeServicePointMapper()
            ->mapShipmentTypeServiceTypeEntitiesToShipmentTypeServiceTypeCollectionTransfer(
                $shipmentTypeServiceTypeQuery->find(),
                $shipmentTypeServiceTypeCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function applyShipmentTypeServiceTypeFilters(
        SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery,
        ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
    ): SpyShipmentTypeServiceTypeQuery {
        $shipmentTypeServiceTypeConditionsTransfer = $shipmentTypeServiceTypeCriteriaTransfer->getShipmentTypeServiceTypeConditions();
        if ($shipmentTypeServiceTypeConditionsTransfer === null) {
            return $shipmentTypeServiceTypeQuery;
        }

        if ($shipmentTypeServiceTypeConditionsTransfer->getShipmentTypeIds() !== []) {
            $shipmentTypeServiceTypeQuery->filterByFkShipmentType_In($shipmentTypeServiceTypeConditionsTransfer->getShipmentTypeIds());
        }

        return $shipmentTypeServiceTypeQuery;
    }

    /**
     * @param \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function applyShipmentTypeServiceTypeSorting(
        SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery,
        ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
    ): SpyShipmentTypeServiceTypeQuery {
        $sortTransfers = $shipmentTypeServiceTypeCriteriaTransfer->getSortCollection();
        foreach ($sortTransfers as $sortTransfer) {
            $shipmentTypeServiceTypeQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $shipmentTypeServiceTypeQuery;
    }

    /**
     * @param \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyShipmentTypeServiceTypePagination(
        SpyShipmentTypeServiceTypeQuery $shipmentTypeServiceTypeQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($shipmentTypeServiceTypeQuery->count());

            $shipmentTypeServiceTypeQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $shipmentTypeServiceTypeQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $paginationModel = $shipmentTypeServiceTypeQuery->paginate(
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

        return $shipmentTypeServiceTypeQuery;
    }
}
