<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

class ShipmentMethodExtender implements ShipmentMethodExtenderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface
     */
    protected $shipmentFetcher;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface $shipmentFetcher
     */
    public function __construct(ShipmentFetcherInterface $shipmentFetcher)
    {
        $this->shipmentFetcher = $shipmentFetcher;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function extendShipmentMethodTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer): ShipmentMethodTransfer
    {
        $newShipmentTransfer = $this->shipmentFetcher
            ->findActiveShipmentMethodWithPricesAndCarrierById($shipmentMethodTransfer->getIdShipmentMethod());

        if ($newShipmentTransfer === null) {
            return $shipmentMethodTransfer;
        }

        $methodPrice = $this->shipmentFetcher
            ->findMethodPriceByShipmentMethodAndCurrentStoreCurrency($newShipmentTransfer, $orderTransfer->getCurrencyIsoCode());

        if ($methodPrice === null) {
            return $shipmentMethodTransfer;
        }

        $price = $this->getPrice($orderTransfer, $methodPrice);

        $newShipmentTransfer
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setStoreCurrencyPrice($price);

        return $newShipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentPriceTransfer $shipmentMethodPriceTransfer
     *
     * @return int
     */
    protected function getPrice(OrderTransfer $orderTransfer, ShipmentPriceTransfer $shipmentMethodPriceTransfer): int
    {
        return $orderTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $shipmentMethodPriceTransfer->getDefaultGrossPrice() :
            $shipmentMethodPriceTransfer->getDefaultNetPrice();
    }
}
