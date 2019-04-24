<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
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
            ->mapTaxSetEntityToTaxSetTransfer($shipmentMethodEntity->getTaxSet(), new TaxSetTransfer());

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

        return $this->hydrateShipmentTransfersFromShipmentEntities($salesOrderShipments);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[][]
     */
    public function getItemIdsGroupedByShipmentIds(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIdsWithShipmentIds = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder($orderTransfer->getIdSalesOrder())
            ->select([SpySalesOrderItemTableMap::COL_FK_SALES_SHIPMENT, SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM])
            ->find();

        if ($salesOrderItemIdsWithShipmentIds->count() === 0) {
            return [];
        }

        return $this->groupSalesOrderItemIdsByShipmentId($salesOrderItemIdsWithShipmentIds);
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

        return $this->hydrateShipmentMethodTransfersFromShipmentMethodEntities($salesShipmentMethods);
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
            $shipmentMethodNames[$shipmentTransfer->getMethod()->getName()] = $shipmentTransfer->getMethod()->getName();
        }

        return $shipmentMethodNames;
    }

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected function hydrateShipmentTransfersFromShipmentEntities(
        iterable $salesOrderShipments
    ): array {
        $shipmentMapper = $this->getFactory()->createShipmentMapper();
        $shipmentTransfers = [];

        foreach ($salesOrderShipments as $salesShipmentEntity) {
            $shipmentTransfers[] = $shipmentMapper->mapShipmentEntityToShipmentTransferWithDetails(new ShipmentTransfer(), $salesShipmentEntity);
        }

        return $shipmentTransfers;
    }

    /**
     * @param iterable|\Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]|\Propel\Runtime\Collection\ObjectCollection $salesShipmentMethods
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected function hydrateShipmentMethodTransfersFromShipmentMethodEntities(
        iterable $salesShipmentMethods
    ): array {
        $shipmentMapper = $this->getFactory()->createShipmentMapper();
        $shipmentMehtodTransfers = [];

        foreach ($salesShipmentMethods as $salesShipmentMethodEntity) {
            $shipmentMehtodTransfers[] = $shipmentMapper->mapShipmentMethodEntityToShipmentMehtodTransfer(new ShipmentMethodTransfer(), $salesShipmentMethodEntity);
        }

        return $shipmentMehtodTransfers;
    }

    /**
     * @param iterable|array $salesOrderItemIdsWithShipmentIds
     *
     * @return int[][]
     */
    protected function groupSalesOrderItemIdsByShipmentId(iterable $salesOrderItemIdsWithShipmentIds): array {
        $groupedResult = [];

        foreach ($salesOrderItemIdsWithShipment as ['shipmentId' => $shipmentId, 'orderItemId' => $orderItemId]) {
            $groupedResult[$shipmentId] = $orderItemId;
        }

        return $groupedResult;
    }
}
