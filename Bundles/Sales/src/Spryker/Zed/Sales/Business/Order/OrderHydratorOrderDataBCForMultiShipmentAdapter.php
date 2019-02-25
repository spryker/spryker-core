<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Shared\Shipment\ShipmentConstants;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class OrderHydratorOrderDataBCForMultiShipmentAdapter implements OrderHydratorOrderDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function adapt(SpySalesOrder $orderEntity): SpySalesOrder
    {
        if ($this->assertThatItemTransfersHaveShipmentAndShippingAddressAndShipmentExpense($orderEntity)) {
            return $orderEntity;
        }

        if ($this->assertThatOrderHasNoShippingAddress($orderEntity)) {
            return $orderEntity;
        }

        $orderExpenseEntity = $this->findOrderShipmentExpense($orderEntity);
        if ($orderExpenseEntity === null) {
            return $orderEntity;
        }

        foreach ($orderEntity->getItems() as $orderItemEntity) {
            if ($this->assertThatItemTransferHasShipmentWithShippingAddressAndShipmentExpense($orderItemEntity)) {
                continue;
            }

            $this->setItemTransferShipmentAndShippingAddressAndShipmentExpenseForBC($orderItemEntity, $orderEntity, $orderExpenseEntity);
        }

        return $orderEntity;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function assertThatItemTransfersHaveShipmentAndShippingAddressAndShipmentExpense(SpySalesOrder $orderEntity): bool
    {
        foreach ($orderEntity->getItems() as $orderItemEntity) {
            if ($orderItemEntity->getSpySalesShipment() === null
                || $orderItemEntity->getSpySalesShipment()->getSpySalesOrderAddress() === null
                || $orderItemEntity->getSpySalesShipment()->getExpense() === null
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function assertThatOrderHasNoShippingAddress(SpySalesOrder $orderEntity): bool
    {
        return $orderEntity->getShippingAddress() === null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense|null
     */
    protected function findOrderShipmentExpense(SpySalesOrder $orderEntity): ?SpySalesExpense
    {
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            if ($expenseEntity->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            return $expenseEntity;
        }

        return null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShippingAddressAndShipmentExpense(SpySalesOrderItem $orderItemEntity): bool
    {
        return ($orderItemEntity->getSpySalesShipment() !== null
            && $orderItemEntity->getSpySalesShipment()->getSpySalesOrderAddress() !== null
            && $orderItemEntity->getSpySalesShipment()->getExpense() !== null
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected function getShipmentEntityForBC(SpySalesOrderItem $orderItemEntity): SpySalesShipment
    {
        if ($orderItemEntity->getSpySalesShipment() !== null) {
            return $orderItemEntity->getSpySalesShipment();
        }

        return new SpySalesShipment();
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function getShippingAddressForBC(SpySalesOrderItem $orderItemEntity, SpySalesOrder $orderEntity): SpySalesOrderAddress
    {
        if ($orderItemEntity->getSpySalesShipment() !== null && $orderItemEntity->getSpySalesShipment()->getSpySalesOrderAddress() !== null) {
            return $orderItemEntity->getSpySalesShipment()->getSpySalesOrderAddress();
        }

        return $orderEntity->getShippingAddress();
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $orderExpenseEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function getShipmentExpenseEntityForBC(SpySalesOrderItem $orderItemEntity, SpySalesExpense $orderExpenseEntity): SpySalesExpense
    {
        if ($orderItemEntity->getShipment() !== null && $orderItemEntity->getShipment()->getExpense() !== null) {
            return $orderItemEntity->getShipment()->getExpense();
        }

        return $orderExpenseEntity;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $orderExpenseEntity
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShippingAddressAndShipmentExpenseForBC(
        SpySalesOrderItem $orderItemEntity,
        SpySalesOrder $orderEntity,
        SpySalesExpense $orderExpenseEntity
    ): void {
        $shipmentEntity = $this->getShipmentEntityForBC($orderItemEntity);
        $orderItemEntity->setSpySalesShipment($shipmentEntity);

        $shippingAddressEntity = $this->getShippingAddressForBC($orderItemEntity, $orderEntity);
        $shipmentExpenseTransfer = $this->getShipmentExpenseEntityForBC($orderItemEntity, $orderExpenseEntity);
        $shipmentEntity->setSpySalesOrderAddress($shippingAddressEntity)
            ->setExpense($shipmentExpenseTransfer);
    }
}
