<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesShipmentCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends AbstractRepository implements ShipmentRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_MAX_TAX_RATE = 'maxTaxRate';

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     * @param string $countryIso2Code
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSetByShipmentMethodAndCountryIso2Code(
        ShipmentMethodTransfer $methodTransfer,
        string $countryIso2Code
    ): ?TaxSetTransfer {
        /** @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null $shipmentMethodEntity */
        $shipmentMethodEntity = $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($methodTransfer->getIdShipmentMethod())
            ->leftJoinWithTaxSet()
            ->useTaxSetQuery()
                ->useSpyTaxSetTaxQuery()
                    ->useSpyTaxRateQuery()
                        ->useCountryQuery()
                            ->filterByIso2Code($countryIso2Code)
                        ->endUse()
                        ->_or()
                        ->filterByName(TaxConstants::TAX_EXEMPT_PLACEHOLDER)
                    ->endUse()
                ->endUse()
                ->groupBy(SpyTaxSetTableMap::COL_NAME)
                ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', static::COL_MAX_TAX_RATE)
            ->endUse()
            ->findOne();

        if ($shipmentMethodEntity === null || $shipmentMethodEntity->getTaxSet() === null) {
            return null;
        }

        $taxSetTransfer = $this->getFactory()
            ->createTaxSetMapper()
            ->mapTaxSetEntityToTaxSetTransfer(
                $shipmentMethodEntity->getTaxSet(),
                new TaxSetTransfer(),
            );

        return $taxSetTransfer->setEffectiveRate($shipmentMethodEntity->getVirtualColumn(static::COL_MAX_TAX_RATE));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ShipmentTransfer>
     */
    public function findShipmentTransfersByOrder(OrderTransfer $orderTransfer): array
    {
        $salesOrderShipments = $this->getFactory()
            ->createSalesShipmentQuery()
            ->leftJoinWithSpySalesOrderAddress()
            ->filterByFkSalesOrder($orderTransfer->getIdSalesOrder())
            ->find();

        if ($salesOrderShipments->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createShipmentMapper()
            ->mapShipmentEntitiesToShipmentTransfers($salesOrderShipments, []);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $defaultShipmentTransfer
     *
     * @return array<array<int>>
     */
    public function getItemIdsGroupedByShipmentIds(
        OrderTransfer $orderTransfer,
        ?ShipmentTransfer $defaultShipmentTransfer = null
    ): array {
        $salesOrderItemIdsWithShipmentIds = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder($orderTransfer->getIdSalesOrder())
            ->select([SpySalesOrderItemTableMap::COL_FK_SALES_SHIPMENT, SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM])
            ->find();

        if ($salesOrderItemIdsWithShipmentIds->count() === 0) {
            return [];
        }

        return $this->groupSalesOrderItemIdsByShipmentId($salesOrderItemIdsWithShipmentIds, $defaultShipmentTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTransfer> $shipmentTransfers
     *
     * @return array<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function findShipmentMethodTransfersByShipment(array $shipmentTransfers): array
    {
        if (count($shipmentTransfers) === 0) {
            return [];
        }

        $shipmentMethodNames = $this->getShipmentMethodNames($shipmentTransfers);

        $salesShipmentMethods = $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIsActive(true)
            ->filterByName_In($shipmentMethodNames)
            ->find();

        if ($salesShipmentMethods->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntitiesToShipmentMethodTransfers($salesShipmentMethods, []);
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByName(string $shipmentMethodName): ?ShipmentMethodTransfer
    {
        $salesShipmentMethodEntity = $this->queryMethodsWithMethodPricesAndCarrier()
            ->filterByName($shipmentMethodName)
            ->find()
            ->getFirst();

        if ($salesShipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer(),
            );
    }

    /**
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer
    {
        $shipmentMethodEntity = $this->queryMethodsWithMethodPricesAndCarrier()
            ->filterByShipmentMethodKey($shipmentMethodKey)
            ->find()
            ->getFirst();

        if ($shipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $shipmentMethodEntity,
                new ShipmentMethodTransfer(),
            );
    }

    /**
     * @param int $idShipmentMethod
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByIdAndIdStore(int $idShipmentMethod, int $idStore): ?ShipmentMethodTransfer
    {
        /** @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null $salesShipmentMethodEntity */
        $salesShipmentMethodEntity = $this->queryMethods()
            ->useShipmentMethodStoreQuery()
                ->filterByFkStore($idStore)
            ->endUse()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->filterByPricePlugin(null, Criteria::ISNOTNULL)
            ->_or()
            ->useShipmentMethodPriceQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkStore($idStore)
            ->endUse()
            ->findOne();

        if ($salesShipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer(),
            );
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByIdWithPricesAndCarrier(int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        $salesShipmentMethodEntity = $this->queryMethodsWithMethodPricesAndCarrier()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->find()
            ->getFirst();

        if ($salesShipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer(),
            );
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Shipment\Persistence\ShipmentRepository::getSalesShipmentCollection()} instead.
     *
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idShipmentMethod): ?ShipmentTransfer
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesShipment|null $salesShipmentEntity */
        $salesShipmentEntity = $this->getFactory()
            ->createSalesShipmentQuery()
            ->filterByIdSalesShipment($idShipmentMethod)
            ->leftJoinWithSpySalesOrderAddress()
            ->useSpySalesOrderAddressQuery()
                ->leftJoinCountry()
            ->endUse()
            ->findOne();

        if ($salesShipmentEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMapper()
            ->mapShipmentEntityToShipmentTransferWithDetails($salesShipmentEntity, new ShipmentTransfer());
    }

    /**
     * @return array<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getActiveShipmentMethods(): array
    {
        $shipmentMethodList = [];
        $shipmentMethodEntities = $this->queryActiveMethodsWithMethodPricesAndCarrier()->find();

        if ($shipmentMethodEntities->count() === 0) {
            return $shipmentMethodList;
        }

        foreach ($shipmentMethodEntities as $shipmentMethodEntity) {
            $shipmentMethodTransfer = $this->getFactory()
                ->createShipmentMethodMapper()
                ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                    $shipmentMethodEntity,
                    new ShipmentMethodTransfer(),
                );

            $shipmentMethodList[] = $shipmentMethodTransfer;
        }

        return $shipmentMethodList;
    }

    /**
     * @param int $idStore
     *
     * @return array<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getActiveShipmentMethodsForStore(int $idStore): array
    {
        $shipmentMethodList = [];
        $shipmentMethodEntities = $this->getActiveMethodsWithMethodPricesAndCarrierForStore($idStore);

        if ($shipmentMethodEntities->count() === 0) {
            return $shipmentMethodList;
        }

        foreach ($shipmentMethodEntities as $shipmentMethodEntity) {
            $shipmentMethodTransfer = $this->getFactory()
                ->createShipmentMethodMapper()
                ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                    $shipmentMethodEntity,
                    new ShipmentMethodTransfer(),
                );

            $shipmentMethodList[] = $shipmentMethodTransfer;
        }

        return $shipmentMethodList;
    }

    /**
     * @param int $idShipmentMethod
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\ShipmentPriceTransfer|null
     */
    public function findShipmentMethodPrice(int $idShipmentMethod, int $idStore, int $idCurrency): ?ShipmentPriceTransfer
    {
        $shipmentMethodPriceEntity = $this->queryMethodPriceByShipmentMethodAndStoreCurrency(
            $idShipmentMethod,
            $idStore,
            $idCurrency,
        )->findOne();

        if ($shipmentMethodPriceEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodPriceEntityToShipmentPriceTransfer(
                $shipmentMethodPriceEntity,
                new ShipmentPriceTransfer(),
            );
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function hasShipmentMethodByIdShipmentMethod(int $idShipmentMethod): bool
    {
        return $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->exists();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function hasActiveShipmentMethodByIdShipmentMethod(int $idShipmentMethod): bool
    {
        return $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->filterByIsActive(true)
            ->exists();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findSalesOrderById(int $idSalesOrder): ?OrderTransfer
    {
        $salesOrderEntity = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        if ($salesOrderEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentOrderMapper()
            ->mapSalesOrderEntityToOrderTransfer($salesOrderEntity, new OrderTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer|null
     */
    public function findShipmentCarrier(ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer): ?ShipmentCarrierTransfer
    {
        $shipmentCarrierQuery = $this->getFactory()->createShipmentCarrierQuery();

        if ($shipmentCarrierRequestTransfer->getIdCarrier() === null) {
            $shipmentCarrierRequestTransfer->requireCarrierName();
        }

        if ($shipmentCarrierRequestTransfer->getCarrierName() === null) {
            $shipmentCarrierRequestTransfer->requireIdCarrier();
        }

        if ($shipmentCarrierRequestTransfer->getIdCarrier() !== null) {
            $shipmentCarrierQuery->filterByIdShipmentCarrier($shipmentCarrierRequestTransfer->getIdCarrier());
        }

        if ($shipmentCarrierRequestTransfer->getCarrierName() !== null) {
            $shipmentCarrierQuery->filterByName($shipmentCarrierRequestTransfer->getCarrierName());
        }

        if ($shipmentCarrierRequestTransfer->getExcludedCarrierIds() !== []) {
            $shipmentCarrierQuery->filterByIdShipmentCarrier($shipmentCarrierRequestTransfer->getExcludedCarrierIds(), Criteria::NOT_IN);
        }

        $shipmentCarrierEntity = $shipmentCarrierQuery->findOne();
        if ($shipmentCarrierEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentCarrierMapper()
            ->mapShipmentCarrierEntityToShipmentCarrierTransfer($shipmentCarrierEntity, new ShipmentCarrierTransfer());
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ShipmentTransfer> $shipmentTransfers
     *
     * @return array<string>
     */
    protected function getShipmentMethodNames(iterable $shipmentTransfers): array
    {
        $shipmentMethodNames = [];
        foreach ($shipmentTransfers as $shipmentTransfer) {
            $shipmentMethodTransfer = $shipmentTransfer->getMethod();
            if ($shipmentMethodTransfer === null) {
                continue;
            }

            $shipmentMethodName = $shipmentMethodTransfer->getName();
            if ($shipmentMethodName === '') {
                continue;
            }

            $shipmentMethodNames[$shipmentMethodName] = $shipmentMethodName;
        }

        return $shipmentMethodNames;
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethodTransferByName(iterable $shipmentMethodTransfers, ShipmentTransfer $shipmentTransfer): ?ShipmentMethodTransfer
    {
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            if ($shipmentTransfer->getMethod()->getName() === $shipmentMethodTransfer->getName()) {
                return clone $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param iterable|array $salesOrderItemIdsWithShipmentIds
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $defaultShipmentTransfer
     *
     * @return array<array<int>>
     */
    protected function groupSalesOrderItemIdsByShipmentId(
        iterable $salesOrderItemIdsWithShipmentIds,
        ?ShipmentTransfer $defaultShipmentTransfer = null
    ): array {
        $groupedResult = [];
        $idDefaultShipmentTransfer = null;

        if ($defaultShipmentTransfer !== null) {
            $idDefaultShipmentTransfer = $defaultShipmentTransfer->getIdSalesShipment();
        }

        foreach (
            $salesOrderItemIdsWithShipmentIds as [
            SpySalesOrderItemTableMap::COL_FK_SALES_SHIPMENT => $shipmentId,
            SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM => $orderItemId,
            ]
        ) {
            $shipmentId = $shipmentId ?? $idDefaultShipmentTransfer;
            if (!isset($groupedResult[$shipmentId])) {
                $groupedResult[$shipmentId] = [];
            }

            $groupedResult[$shipmentId][] = $orderItemId;
        }

        return $groupedResult;
    }

    /**
     * @module Currency
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function queryMethodsWithMethodPricesAndCarrier(): SpyShipmentMethodQuery
    {
        /** @phpstan-var \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery */
        return $this->queryMethods()
            ->leftJoinWithShipmentCarrier()
            ->joinWithShipmentMethodPrice()
            ->useShipmentMethodPriceQuery()
                ->joinWithCurrency()
                ->joinWithStore()
            ->endUse();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function queryMethods(): SpyShipmentMethodQuery
    {
        return $this->getFactory()->createShipmentMethodQuery();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function queryActiveMethodsWithMethodPricesAndCarrier(): SpyShipmentMethodQuery
    {
        return $this->queryMethodsWithMethodPricesAndCarrier()
            ->filterByIsActive(true);
    }

    /**
     * @param int $idStore
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Shipment\Persistence\SpyShipmentMethod>
     */
    protected function getActiveMethodsWithMethodPricesAndCarrierForStore(int $idStore): ObjectCollection
    {
        $shipmentMethodEntities = $this->queryMethods()
            ->filterByIsActive(true)
            ->groupByIdShipmentMethod()
            ->leftJoinWithShipmentCarrier()
            ->useShipmentMethodStoreQuery()
                ->filterByFkStore($idStore)
            ->endUse()
            ->filterByPricePlugin(null, Criteria::ISNOTNULL)
            ->_or()
            ->useShipmentMethodPriceQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkStore($idStore)
            ->endUse()
            ->find();

        if ($shipmentMethodEntities->count() === 0) {
            return $shipmentMethodEntities;
        }

        return $this->expandShipmentMethodEntitiesWithShipmentMethodPricesForStore($shipmentMethodEntities, $idStore);
    }

    /**
     * @param int $idShipmentMethod
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    protected function queryMethodPriceByShipmentMethodAndStoreCurrency(
        int $idShipmentMethod,
        int $idStore,
        int $idCurrency
    ): SpyShipmentMethodPriceQuery {
        return $this->queryMethodPrices()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->filterByFkStore($idStore)
            ->filterByFkCurrency($idCurrency);
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    protected function queryMethodPrices(): SpyShipmentMethodPriceQuery
    {
        return $this->getFactory()->createShipmentMethodPriceQuery();
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesShipment
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function findSalesOrderItemsBySalesShipmentId(int $idSalesOrder, int $idSalesShipment): ArrayObject
    {
        $salesOrderItemEntities = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByFkSalesShipment($idSalesShipment)
            ->_or()
            ->filterByFkSalesShipment(null)
            ->find();

        if ($salesOrderItemEntities->count() === 0) {
            return new ArrayObject();
        }

        $salesOrderItemMapper = $this->getFactory()->createShipmentSalesOrderItemMapper();

        $itemTransfers = new ArrayObject();
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfer = $salesOrderItemMapper
                ->mapSalesOrderItemEntityToItemTransfer($salesOrderItemEntity, new ItemTransfer());

            $itemTransfers->append($itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        $shipmentMethodTransfer->requireName()
            ->requireFkShipmentCarrier();

        return !$this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByName($shipmentMethodTransfer->getName())
            ->filterByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod(), Criteria::NOT_EQUAL)
            ->filterByFkShipmentCarrier($shipmentMethodTransfer->getFkShipmentCarrier())
            ->exists();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdShipmentMethod(int $idShipmentMethod): StoreRelationTransfer
    {
        $shipmentMethodStoreEntities = $this->getFactory()
            ->createShipmentMethodStoreQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->leftJoinWithStore()
            ->find();

        $storeRelationTransfer = (new StoreRelationTransfer())->setIdEntity($idShipmentMethod);

        return $this->getFactory()
            ->createStoreRelationMapper()
            ->mapShipmentMethodStoreEntitiesToStoreRelationTransfer($shipmentMethodStoreEntities, $storeRelationTransfer);
    }

    /**
     * @return array<\Generated\Shared\Transfer\ShipmentCarrierTransfer>
     */
    public function getActiveShipmentCarriers(): array
    {
        $shipmentCarrierEntityCollection = $this->getFactory()
            ->createShipmentCarrierQuery()
            ->filterByIsActive(true)
            ->find();

        return $this->getFactory()
            ->createShipmentCarrierMapper()
            ->mapShipmentCarrierEntityCollectionToShipmentCarrierTransferCollection($shipmentCarrierEntityCollection, []);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentCollectionTransfer
     */
    public function getSalesShipmentCollection(
        SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
    ): SalesShipmentCollectionTransfer {
        $salesShipmentQuery = $this->getFactory()->createSalesShipmentQuery();

        if ($salesShipmentCriteriaTransfer->getSalesShipmentConditions() && $salesShipmentCriteriaTransfer->getSalesShipmentConditionsOrFail()->getWithOrderItems()) {
            $salesShipmentQuery->leftJoinWithSpySalesOrderItem();
        }
        $salesShipmentQuery = $this->applySalesShipmentFilters($salesShipmentQuery, $salesShipmentCriteriaTransfer);
        $salesShipmentQuery = $this->applySalesShipmentSorting($salesShipmentQuery, $salesShipmentCriteriaTransfer);

        $paginationTransfer = $salesShipmentCriteriaTransfer->getPagination();
        $salesShipmentCollectionTransfer = new SalesShipmentCollectionTransfer();

        if ($paginationTransfer !== null) {
            $salesShipmentQuery = $this->applySalesShipmentPagination($salesShipmentQuery, $paginationTransfer);
            $salesShipmentCollectionTransfer->setPagination($paginationTransfer);
        }

        $salesShipmentEntities = $salesShipmentQuery->find();
        if ($salesShipmentEntities->count() === 0) {
            return $salesShipmentCollectionTransfer;
        }

        return $this->getFactory()
            ->createShipmentMapper()
            ->mapSalesShipmentEntityCollectionToSalesShipmentCollectionTransfer(
                $salesShipmentEntities,
                $salesShipmentCollectionTransfer,
                $salesShipmentCriteriaTransfer->getSalesShipmentConditions(),
            );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Shipment\Persistence\SpyShipmentMethod> $shipmentMethodEntities
     * @param int $idStore
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Shipment\Persistence\SpyShipmentMethod>
     */
    protected function expandShipmentMethodEntitiesWithShipmentMethodPricesForStore(
        ObjectCollection $shipmentMethodEntities,
        int $idStore
    ): ObjectCollection {
        $shipmentMethodWithoutPricePluginIds = $this->extractShipmentMethodWithoutPricePluginIds($shipmentMethodEntities);

        $shipmentMethodPriceEntities = $this->getFactory()
            ->createShipmentMethodPriceQuery()
            ->innerJoinWithCurrency()
            ->filterByFkShipmentMethod_In($shipmentMethodWithoutPricePluginIds)
            ->filterByFkStore($idStore)
            ->find();

        if ($shipmentMethodPriceEntities->count() === 0) {
            return $shipmentMethodEntities;
        }

        foreach ($shipmentMethodEntities as $shipmentMethodEntity) {
            $this->expandShipmentMethodEntityWithShipmentMethodPrices($shipmentMethodEntity, $shipmentMethodPriceEntities);
        }

        return $shipmentMethodEntities;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $shipmentMethodEntities
     *
     * @return array<int>
     */
    protected function extractShipmentMethodWithoutPricePluginIds(ObjectCollection $shipmentMethodEntities): array
    {
        $idShipmentMethodsWithoutPricePlugins = [];
        foreach ($shipmentMethodEntities as $shipmentMethodEntity) {
            if ($shipmentMethodEntity->getPricePlugin()) {
                continue;
            }
            $idShipmentMethodsWithoutPricePlugins[] = $shipmentMethodEntity->getIdShipmentMethod();
        }

        return $idShipmentMethodsWithoutPricePlugins;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice> $shipmentMethodPriceEntities
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function expandShipmentMethodEntityWithShipmentMethodPrices(
        SpyShipmentMethod $shipmentMethodEntity,
        ObjectCollection $shipmentMethodPriceEntities
    ): SpyShipmentMethod {
        foreach ($shipmentMethodPriceEntities as $shipmentMethodPriceEntity) {
            if ($shipmentMethodEntity->getIdShipmentMethod() === $shipmentMethodPriceEntity->getFkShipmentMethod()) {
                $shipmentMethodEntity->addShipmentMethodPrice($shipmentMethodPriceEntity);
            }
        }

        return $shipmentMethodEntity;
    }

    /**
     * @module Sales
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery $salesShipmentQuery
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    protected function applySalesShipmentFilters(
        SpySalesShipmentQuery $salesShipmentQuery,
        SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
    ): SpySalesShipmentQuery {
        $salesShipmentConditionsTransfer = $salesShipmentCriteriaTransfer->getSalesShipmentConditions();
        if ($salesShipmentConditionsTransfer === null) {
            return $salesShipmentQuery;
        }

        $orderItemUuids = $salesShipmentConditionsTransfer->getOrderItemUuids();
        if ($orderItemUuids !== []) {
            $salesShipmentQuery->useSpySalesOrderItemQuery()
                    ->filterByUuid_In($orderItemUuids)
                ->endUse()
                ->distinct();
        }

        $salesOrderItemIds = $salesShipmentConditionsTransfer->getSalesOrderItemIds();
        if ($salesOrderItemIds !== []) {
            $salesShipmentQuery->useSpySalesOrderItemQuery()
                    ->filterByIdSalesOrderItem_In($salesOrderItemIds)
                ->endUse()
                ->distinct();
        }

        $salesShipmentIds = $salesShipmentConditionsTransfer->getSalesShipmentIds();
        if ($salesShipmentIds !== []) {
            $salesShipmentQuery->filterByIdSalesShipment_In($salesShipmentIds);
        }

        return $salesShipmentQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery $salesShipmentQuery
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    protected function applySalesShipmentSorting(
        SpySalesShipmentQuery $salesShipmentQuery,
        SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
    ): SpySalesShipmentQuery {
        $sortCollection = $salesShipmentCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $salesShipmentQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $salesShipmentQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery $salesShipmentQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySalesShipmentPagination(
        SpySalesShipmentQuery $salesShipmentQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $salesShipmentQuery->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $salesShipmentQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $paginationModel = $salesShipmentQuery->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $this->getFactory()->createPaginationMapper()->mapPropelModelPagerToPaginationTransfer(
                $paginationModel,
                $paginationTransfer,
            );

            return $paginationModel->getQuery();
        }

        return $salesShipmentQuery;
    }
}
