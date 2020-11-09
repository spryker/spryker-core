<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

class OrderShipmentMapper implements OrderShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        $restOrderDetailsAttributesTransfer = $this->expandRestOrderDetailsAttributesTransferWithItemShipmentId(
            $orderTransfer,
            $restOrderDetailsAttributesTransfer
        );

        $restOrderDetailsAttributesTransfer = $this->expandRestOrderDetailsAttributesTransferWithExpenseShipmentId(
            $orderTransfer,
            $restOrderDetailsAttributesTransfer
        );

        return $restOrderDetailsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer $restOrderShipmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer
     */
    public function mapShipmentGroupTransferToRestOrderShipmentsAttributesTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        RestOrderShipmentsAttributesTransfer $restOrderShipmentsAttributesTransfer
    ): RestOrderShipmentsAttributesTransfer {
        $itemUuids = [];
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemUuids[] = $itemTransfer->getUuid();
        }

        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        $restOrderShipmentsAttributesTransfer
            ->fromArray($shipmentTransfer->toArray(), true)
            ->setItemUuids($itemUuids)
            ->setMethodName($shipmentTransfer->getMethod()->getName())
            ->setCarrierName($shipmentTransfer->getCarrier()->getName());

        $restOrderShipmentsAttributesTransfer
            ->getShippingAddress()
            ->setCountry($shipmentTransfer->getShippingAddress()->getCountry()->getName());

        return $restOrderShipmentsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    protected function expandRestOrderDetailsAttributesTransferWithItemShipmentId(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        foreach ($restOrderDetailsAttributesTransfer->getItems() as $key => $restOrderItemsAttributesTransfer) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                if ($restOrderItemsAttributesTransfer->getUuid() !== $itemTransfer->getUuid()) {
                    continue;
                }

                $restOrderItemsAttributesTransfer->setIdShipment($itemTransfer->getShipment()->getIdSalesShipment());
                $restOrderDetailsAttributesTransfer->getItems()->offsetSet($key, $restOrderItemsAttributesTransfer);

                break;
            }
        }

        return $restOrderDetailsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    protected function expandRestOrderDetailsAttributesTransferWithExpenseShipmentId(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        foreach ($restOrderDetailsAttributesTransfer->getExpenses() as $key => $restOrderExpensesAttributesTransfer) {
            foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
                if ($restOrderExpensesAttributesTransfer->getIdSalesExpense() !== $expenseTransfer->getIdSalesExpense()) {
                    continue;
                }

                $restOrderExpensesAttributesTransfer->setIdShipment($expenseTransfer->getShipment()->getIdSalesShipment());
                $restOrderDetailsAttributesTransfer->getExpenses()->offsetSet($key, $restOrderExpensesAttributesTransfer);

                break;
            }
        }

        return $restOrderDetailsAttributesTransfer;
    }
}
