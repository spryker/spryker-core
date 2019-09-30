<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderShipmentTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

class OrderShipmentMapper implements OrderShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject $restShipmentMethodTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderShipmentTransfer[]
     */
    public function mapOrderTransferToRestOrderShipmentTransfers(
        OrderTransfer $orderTransfer,
        ArrayObject $restShipmentMethodTransfers
    ): ArrayObject {
        foreach ($orderTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $restShipmentMethodTransfers->append(
                $this->createRestShipmentMethodTransfer(
                    $shipmentMethodTransfer,
                    $orderTransfer->getExpenses(),
                    $orderTransfer->getCurrencyIsoCode()
                )
            );
        }

        return $restShipmentMethodTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function findDefaultShipmentMethodPriceTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        OrderTransfer $orderTransfer
    ): ?MoneyValueTransfer {
        foreach ($shipmentMethodTransfer->getPrices() as $shipmentMethodPriceTransfer) {
            if ($this->isDefaultShipmentMethodPrice($orderTransfer, $shipmentMethodPriceTransfer)) {
                return $shipmentMethodPriceTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $shipmentMethodPriceTransfer
     *
     * @return bool
     */
    protected function isDefaultShipmentMethodPrice(
        OrderTransfer $orderTransfer,
        MoneyValueTransfer $shipmentMethodPriceTransfer
    ): bool {
        $shipmentMethodPriceTransfer->requireStore();
        $shipmentMethodPriceTransfer->requireCurrency();

        return $shipmentMethodPriceTransfer->getStore()->getName() === $orderTransfer->getStore()
            && $shipmentMethodPriceTransfer->getCurrency()->getCode() === $orderTransfer->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentTransfer
     */
    protected function createRestShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ArrayObject $expenseTransfers,
        string $currencyIsoCode
    ): RestOrderShipmentTransfer {
        $restOrderShipmentTransfer = new RestOrderShipmentTransfer();
        $restOrderShipmentTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $restOrderShipmentTransfer->setShipmentMethodName($shipmentMethodTransfer->getName());
        $restOrderShipmentTransfer->setCurrencyIsoCode($currencyIsoCode);

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE
                && $shipmentMethodTransfer->getFkSalesExpense()) {
                $restOrderShipmentTransfer
                    ->setDefaultNetPrice($expenseTransfer->getSumNetPrice())
                    ->setDefaultGrossPrice($expenseTransfer->getSumGrossPrice());
            }
        }

        return $restOrderShipmentTransfer;
    }
}
