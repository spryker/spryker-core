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

class OrderResourceShipmentMapper implements OrderResourceShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderShipmentTransfer[]
     */
    public function mapShipmentMethodTransfersToRestOrderShipmentTransfers(OrderTransfer $orderTransfer): ArrayObject
    {
        $restShipmentMethodTransfers = new ArrayObject();

        foreach ($orderTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $defaultShipmentMethodPriceTransfer = $this->findDefaultShipmentMethodPriceTransfer(
                $shipmentMethodTransfer,
                $orderTransfer
            );

            if (!$defaultShipmentMethodPriceTransfer) {
                return $restShipmentMethodTransfers;
            }

            $restShipmentMethodTransfers->append(
                $this->createRestShipmentMethodTransfer($shipmentMethodTransfer, $defaultShipmentMethodPriceTransfer)
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
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $defaultShipmentMethodPriceTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentTransfer
     */
    protected function createRestShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        MoneyValueTransfer $defaultShipmentMethodPriceTransfer
    ): RestOrderShipmentTransfer {
        $defaultShipmentMethodPriceTransfer->requireCurrency();

        return (new RestOrderShipmentTransfer())
            ->setShipmentMethodName($shipmentMethodTransfer->getName())
            ->setCarrierName($shipmentMethodTransfer->getCarrierName())
            ->setDeliveryTime($shipmentMethodTransfer->getDeliveryTime())
            ->setDefaultNetPrice($defaultShipmentMethodPriceTransfer->getNetAmount())
            ->setDefaultGrossPrice($defaultShipmentMethodPriceTransfer->getGrossAmount())
            ->setCurrencyIsoCode($defaultShipmentMethodPriceTransfer->getCurrency()->getCode());
    }
}
