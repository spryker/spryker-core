<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class ShipmentMethodMapper implements ShipmentMethodMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodAttributesTransfers(
        ArrayObject $shipmentMethodTransfers,
        StoreTransfer $storeTransfer
    ): array {
        $restShipmentMethodAttributesTransfers = [];

        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodAttributesTransfers[$shipmentMethodTransfer->getIdShipmentMethod()] =
                $this->mapShipmentMethodTransferToRestShipmentMethodAttributesTransfer(
                    $shipmentMethodTransfer,
                    $storeTransfer
                );
        }

        return $restShipmentMethodAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer
     */
    protected function mapShipmentMethodTransferToRestShipmentMethodAttributesTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): RestShipmentMethodAttributesTransfer {
        $restShipmentMethodAttributesTransfers = (new RestShipmentMethodAttributesTransfer())
            ->fromArray($shipmentMethodTransfer->toArray(), true)
            ->setDefaultGrossPrice($this->findDefaultGrossPrice($shipmentMethodTransfer, $storeTransfer))
            ->setDefaultNetPrice($this->findDefaultNetPrice($shipmentMethodTransfer, $storeTransfer));

        return $restShipmentMethodAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int|null
     */
    protected function findDefaultGrossPrice(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?int {
        foreach ($shipmentMethodTransfer->getPrices() as $priceTransfer) {
            if ($this->checkPriceTransferByCurrencyIsoCodeAndStoreId(
                $priceTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $priceTransfer->getGrossAmount();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $priceTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function checkPriceTransferByCurrencyIsoCodeAndStoreId(
        MoneyValueTransfer $priceTransfer,
        StoreTransfer $storeTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): bool {
        return $priceTransfer->getFkStore() === $storeTransfer->getIdStore()
            && $priceTransfer->getCurrency()->getCode() === $shipmentMethodTransfer->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int|null
     */
    protected function findDefaultNetPrice(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?int {
        foreach ($shipmentMethodTransfer->getPrices() as $priceTransfer) {
            if ($this->checkPriceTransferByCurrencyIsoCodeAndStoreId(
                $priceTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $priceTransfer->getNetAmount();
            }
        }

        return null;
    }
}
