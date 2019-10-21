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

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
interface ShipmentEntityManagerInterface
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
    ): ShipmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    public function updateFkShipmentForOrderItem(ItemTransfer $itemTransfer, ShipmentTransfer $shipmentTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function saveSalesShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function updateShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer;

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteMethodByIdMethod(int $idShipmentMethod): void;

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteShipmentMethodStoreRelationsByIdShipmentMethod(int $idShipmentMethod): void;

    /**
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function deleteShipmentMethodPricesByIdShipmentMethod(int $idShipmentMethod): void;

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function saveSalesExpense(ExpenseTransfer $expenseTransfer, OrderTransfer $orderTransfer): ExpenseTransfer;

    /**
     * @param array $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function removeStoreRelations(array $idStores, int $idShipmentMethod): void;

    /**
     * @param array $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function addStoreRelations(array $idStores, int $idShipmentMethod): void;
}
