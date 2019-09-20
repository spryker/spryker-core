<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends AbstractRepository implements ShipmentRepositoryInterface
{
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
                new TaxSetTransfer()
            );

        return $taxSetTransfer->setEffectiveRate($shipmentMethodEntity->getVirtualColumn(static::COL_MAX_TAX_RATE));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
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
     * @return int[][]
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
     * @param \Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
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
                new ShipmentMethodTransfer()
            );
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodById(int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        $salesShipmentMethodEntity = $this->queryMethods()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->findOne();

        if ($salesShipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer()
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
            ->filterByIsActive(true)
            ->find()
            ->getFirst();

        if ($salesShipmentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer()
            );
    }

    /**
     * @param string $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(string $idShipmentMethod): ?ShipmentTransfer
    {
        $salesShipmentEntity = $this->getFactory()
            ->createSalesShipmentQuery()
            ->leftJoinWithSpySalesOrderAddress()
            ->useSpySalesOrderAddressQuery()
                ->leftJoinCountry()
            ->endUse()
            ->filterByIdSalesShipment($idShipmentMethod)
            ->findOne();

        if ($salesShipmentEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMapper()
            ->mapShipmentEntityToShipmentTransferWithDetails($salesShipmentEntity, new ShipmentTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
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
                    new ShipmentMethodTransfer()
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
            $idCurrency
        )->findOne();

        if ($shipmentMethodPriceEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodPriceEntityToShipmentPriceTransfer(
                $shipmentMethodPriceEntity,
                new ShipmentPriceTransfer()
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
     * @param int $idCarrier
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer|null
     */
    public function findShipmentCarrierById(int $idCarrier): ?ShipmentCarrierTransfer
    {
        $shipmentCarrierEntity = $this->getFactory()
            ->createShipmentCarrierQuery()
            ->filterByIdShipmentCarrier($idCarrier)
            ->findOne();

        if ($shipmentCarrierEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createShipmentCarrierMapper()
            ->mapShipmentCarrierEntityToShipmentCarrierTransfer($shipmentCarrierEntity, new ShipmentCarrierTransfer());
    }

    /**
     * @param string $carrierName
     * @param int|null $excludeIdCarrier
     *
     * @return bool
     */
    public function hasCarrierName($carrierName, ?int $excludeIdCarrier = null): bool
    {
        $query = $this->getFactory()
            ->createShipmentCarrierQuery()
            ->filterByName($carrierName);

        if ($excludeIdCarrier !== null) {
            $query->filterByIdShipmentCarrier($excludeIdCarrier, Criteria::NOT_EQUAL);
        }

        return $query->exists();
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     *
     * @return string[]
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
     * @param iterable|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
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
     * @return int[][]
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

        foreach ($salesOrderItemIdsWithShipmentIds as [
            SpySalesOrderItemTableMap::COL_FK_SALES_SHIPMENT => $shipmentId,
            SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM => $orderItemId,
        ]) {
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
        return $this->queryMethods()
            ->joinWithShipmentMethodPrice()
                ->useShipmentMethodPriceQuery()
                    ->joinWithCurrency()
                ->endUse()
            ->leftJoinWithShipmentCarrier();
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
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
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
}
