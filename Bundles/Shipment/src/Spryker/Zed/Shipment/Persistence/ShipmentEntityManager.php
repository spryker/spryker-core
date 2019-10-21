<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentEntityManager extends AbstractEntityManager implements ShipmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function saveSalesShipment(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer,
        ?ExpenseTransfer $expenseTransfer = null
    ): ShipmentTransfer {
        $salesShipmentEntity = null;
        $idSalesShipment = $shipmentTransfer->getIdSalesShipment();

        if ($idSalesShipment !== null) {
            $salesShipmentEntity = $this->getFactory()
                ->createSalesShipmentQuery()
                ->findOneByIdSalesShipment($idSalesShipment);
        }

        if ($salesShipmentEntity === null) {
            $salesShipmentEntity = new SpySalesShipment();
        }

        $shipmentEntityMapper = $this->getFactory()->createShipmentMapper();
        $salesShipmentEntity = $shipmentEntityMapper
            ->mapShipmentTransferToShipmentEntity($shipmentTransfer, $salesShipmentEntity);
        $salesShipmentEntity = $this->getFactory()->createShipmentOrderMapper()
            ->mapOrderTransferToShipmentEntity($orderTransfer, $salesShipmentEntity);

        if ($expenseTransfer !== null) {
            $salesShipmentEntity = $this->getFactory()->createShipmentExpenseMapper()
                ->mapExpenseTransferToShipmentEntity($expenseTransfer, $salesShipmentEntity);
        }

        $salesShipmentEntity->save();

        return $shipmentEntityMapper->mapShipmentEntityToShipmentTransferWithDetails($salesShipmentEntity, $shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    public function updateFkShipmentForOrderItem(ItemTransfer $itemTransfer, ShipmentTransfer $shipmentTransfer): void
    {
        $orderItemEntity = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOneOrCreate();

        $orderItemEntity->setFkSalesShipment($shipmentTransfer->getIdSalesShipment());

        $orderItemEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function saveSalesShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer
    {
        $shipmentMethodMapper = $this->getFactory()->createShipmentMethodMapper();

        $shipmentMethodEntity = $shipmentMethodMapper
            ->mapShipmentMethodTransferToShipmentMethodEntity($shipmentMethodTransfer, new SpyShipmentMethod());

        $shipmentMethodEntity->save();

        return $shipmentMethodMapper
            ->mapShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity, $shipmentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function updateShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer
    {
        $shipmentMethodTransfer->requireIdShipmentMethod();

        $shipmentMethodEntity = $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
            ->findOne();

        $shipmentMethodMapper = $this->getFactory()->createShipmentMethodMapper();

        $shipmentMethodEntity = $shipmentMethodMapper
            ->mapShipmentMethodTransferToShipmentMethodEntity($shipmentMethodTransfer, $shipmentMethodEntity);

        $shipmentMethodEntity->save();

        return $shipmentMethodMapper
            ->mapShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity, $shipmentMethodTransfer);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteMethodByIdMethod(int $idShipmentMethod): void
    {
        $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->findOne()
            ->delete();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteShipmentMethodStoreRelationsByIdShipmentMethod(int $idShipmentMethod): void
    {
        $this->getFactory()
            ->createShipmentMethodStoreQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->delete();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteShipmentMethodPricesByIdShipmentMethod(int $idShipmentMethod): void
    {
        $this->getFactory()
            ->createShipmentMethodPriceQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function saveSalesExpense(ExpenseTransfer $expenseTransfer, OrderTransfer $orderTransfer): ExpenseTransfer
    {
        $expenseMapper = $this->getFactory()->createShipmentExpenseMapper();

        $salesOrderExpenseEntity = $expenseMapper
            ->mapExpenseTransferToOrderSalesExpenseEntity($expenseTransfer, new SpySalesExpense());

        $salesOrderExpenseEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $salesOrderExpenseEntity->save();

        return $expenseMapper
            ->mapOrderSalesExpenseEntityToExpenseTransfer($salesOrderExpenseEntity, $expenseTransfer);
    }

    /**
     * @param array $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function removeStoreRelations(array $idStores, int $idShipmentMethod): void
    {
        if ($idStores === []) {
            return;
        }

        $this->getFactory()
            ->createShipmentMethodStoreQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->filterByFkStore_In($idStores)
            ->delete();
    }

    /**
     * @param array $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function addStoreRelations(array $idStores, int $idShipmentMethod): void
    {
        foreach ($idStores as $idStore) {
            $shipmentMethodStoreEntity = new SpyShipmentMethodStore();
            $shipmentMethodStoreEntity->setFkStore($idStore)
                ->setFkShipmentMethod($idShipmentMethod)
                ->save();
        }
    }
}
