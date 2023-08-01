<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypePersistenceFactory getFactory()
 */
class ShipmentTypeRepository extends AbstractRepository implements ShipmentTypeRepositoryInterface
{
    /**
     * @uses \Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_method.fk_shipment_type';

    /**
     * @uses \Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD
     *
     * @var string
     */
    protected const COL_ID_SHIPMENT_METHOD = 'spy_shipment_method.id_shipment_method';

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
     * @module Shipment
     *
     * @param list<int> $shipmentMethodIds
     *
     * @return array<int, list<int>>
     */
    public function getShipmentMethodIdsGroupedByIdShipmentType(array $shipmentMethodIds): array
    {
        $shipmentMethodQuery = $this->getFactory()->getShipmentMethodPropelQuery()
            ->filterByIdShipmentMethod_In($shipmentMethodIds)
            ->select([
                static::COL_FK_SHIPMENT_TYPE,
                static::COL_ID_SHIPMENT_METHOD,
            ]);

        $shipmentMethodIdsGroupedByIdShipmentMethod = [];
        foreach ($shipmentMethodQuery->find() as $dataItem) {
            if ($dataItem[static::COL_FK_SHIPMENT_TYPE] === null) {
                continue;
            }

            $idShipmentType = (int)$dataItem[static::COL_FK_SHIPMENT_TYPE];
            $idShipmentMethod = (int)$dataItem[static::COL_ID_SHIPMENT_METHOD];
            $shipmentMethodIdsGroupedByIdShipmentMethod[$idShipmentType][] = $idShipmentMethod;
        }

        return $shipmentMethodIdsGroupedByIdShipmentMethod;
    }

    /**
     * For backward compatibility, this method also returns shipment method IDs of shipment methods that don't have `fkShipmentType` defined.
     *
     * @module Shipment
     * @module Store
     *
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return list<int>
     */
    public function getShipmentMethodIdsByShipmentTypeConditions(array $shipmentTypeUuids, string $storeName): array
    {
        $shipmentTypeUuidsImploded = implode(', ', array_map(function ($uuid) {
            return '\'' . $uuid . '\'';
        }, $shipmentTypeUuids));

        $shipmentMethodIds = $this->getFactory()->getShipmentMethodPropelQuery()
            ->select([
                static::COL_ID_SHIPMENT_METHOD,
            ])
            ->leftJoinShipmentType()
            ->useShipmentTypeQuery()
                ->leftJoinShipmentTypeStore()
                ->useShipmentTypeStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinStore()
                ->endUse()
            ->endUse()
            ->condition('fkShipmentTypeIsNull', sprintf('%s IS NULL', static::COL_FK_SHIPMENT_TYPE))
            ->condition('shipmentTypeUuidIn', sprintf('%s IN (%s)', SpyShipmentTypeTableMap::COL_UUID, $shipmentTypeUuidsImploded))
            ->condition('shipmentTypeIsActive', sprintf('%s = ?', SpyShipmentTypeTableMap::COL_IS_ACTIVE), true)
            ->condition('storeNameEquals', sprintf('%s = ?', SpyStoreTableMap::COL_NAME), $storeName)
            ->combine(['shipmentTypeUuidIn', 'shipmentTypeIsActive', 'storeNameEquals'], Criteria::LOGICAL_AND, 'shipmentTypeConditions')
            ->where(['fkShipmentTypeIsNull', 'shipmentTypeConditions'], Criteria::LOGICAL_OR)
            ->find()
            ->getData();

        return array_map(function (int|string $idShipmentMethod) {
            return (int)$idShipmentMethod;
        }, $shipmentMethodIds);
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
