<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;

class ShipmentMethodPricesMapper implements ShipmentMethodPricesMapperInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(ShipmentToCurrencyInterface $currencyFacade, ShipmentToStoreInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice[] $shipmentMethodPriceEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mapShipmentMethodPriceEntitiesToMoneyValueTransfers(array $shipmentMethodPriceEntities): ArrayObject
    {
        $shipmentMethodMoneyValueTransferCollection = new ArrayObject();
        foreach ($shipmentMethodPriceEntities as $shipmentMethodPriceEntity) {
            $shipmentMethodMoneyValueTransferCollection
                ->append($this->mapShipmentMethodPriceEntityToMoneyValueTransfer($shipmentMethodPriceEntity));
        }

        return $shipmentMethodMoneyValueTransferCollection;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $shipmentMethodPriceEntity
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapShipmentMethodPriceEntityToMoneyValueTransfer(
        SpyShipmentMethodPrice $shipmentMethodPriceEntity
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->fromArray($shipmentMethodPriceEntity->toArray(), true)
            ->setIdEntity($shipmentMethodPriceEntity->getIdShipmentMethodPrice())
            ->setNetAmount($shipmentMethodPriceEntity->getDefaultNetPrice())
            ->setGrossAmount($shipmentMethodPriceEntity->getDefaultGrossPrice())
            ->setCurrency($this->getCurrencyTransferByIdCurrency($shipmentMethodPriceEntity->getFkCurrency()))
            ->setStore($this->getStoreTransferByIdStore($shipmentMethodPriceEntity->getFkStore()));
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferByIdCurrency(int $idCurrency): CurrencyTransfer
    {
        return $this->currencyFacade->getByIdCurrency($idCurrency);
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransferByIdStore(int $idStore): StoreTransfer
    {
        return $this->storeFacade->getStoreById($idStore);
    }
}
