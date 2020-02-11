<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;

class ShipmentMethodExpander implements ShipmentMethodExpanderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface
     */
    protected $shipmentFetcher;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentFetcherInterface $shipmentFetcher
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(ShipmentFetcherInterface $shipmentFetcher, ShipmentToStoreInterface $storeFacade)
    {
        $this->shipmentFetcher = $shipmentFetcher;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function expand(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer): ShipmentMethodTransfer
    {
        $orderTransfer->requireStore()
            ->requireCurrencyIsoCode();
        $shipmentMethodTransfer->requireIdShipmentMethod();

        $newShipmentTransfer = $this->shipmentFetcher
            ->findActiveShipmentMethodWithPricesAndCarrierById($shipmentMethodTransfer->getIdShipmentMethod());

        if ($newShipmentTransfer === null) {
            return $shipmentMethodTransfer;
        }

        $storeTransfer = $this->getOrderStore($orderTransfer);

        $methodPrice = $this->shipmentFetcher
            ->findMethodPriceByShipmentMethodAndCurrentStoreCurrency($newShipmentTransfer, $storeTransfer);

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
     * @return int|null
     */
    protected function getPrice(OrderTransfer $orderTransfer, ShipmentPriceTransfer $shipmentMethodPriceTransfer): ?int
    {
        return $orderTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $shipmentMethodPriceTransfer->getDefaultGrossPrice() :
            $shipmentMethodPriceTransfer->getDefaultNetPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getOrderStore(OrderTransfer $orderTransfer): StoreTransfer
    {
        $storeTransfer = $this->storeFacade->getStoreByName($orderTransfer->getStore());

        return $storeTransfer->setSelectedCurrencyIsoCode($orderTransfer->getCurrencyIsoCode());
    }
}
