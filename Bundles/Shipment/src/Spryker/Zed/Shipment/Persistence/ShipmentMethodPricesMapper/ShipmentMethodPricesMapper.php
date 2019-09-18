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
     * @param MoneyValueTransfer[] $moneyValueTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mapShipmentMethodPriceEntitiesToMoneyValueTransfers(
        array $shipmentMethodPriceEntities,
        array $moneyValueTransfers = []
    ): ArrayObject {
        $shipmentMethodMoneyValueTransferCollection = new ArrayObject($moneyValueTransfers);
        foreach ($shipmentMethodPriceEntities as $shipmentMethodPriceEntity) {
            $moneyValueTransfer = $this->mapShipmentMethodPriceEntityToMoneyValueTransfer($shipmentMethodPriceEntity, new MoneyValueTransfer());
            $shipmentMethodMoneyValueTransferCollection->append($moneyValueTransfer);
        }

        return $shipmentMethodMoneyValueTransferCollection;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $shipmentMethodPriceEntity
     * @param MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapShipmentMethodPriceEntityToMoneyValueTransfer(
        SpyShipmentMethodPrice $shipmentMethodPriceEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        return $moneyValueTransfer
            ->fromArray($shipmentMethodPriceEntity->toArray(), true)
            ->setIdEntity($shipmentMethodPriceEntity->getIdShipmentMethodPrice())
            ->setNetAmount($shipmentMethodPriceEntity->getDefaultNetPrice())
            ->setGrossAmount($shipmentMethodPriceEntity->getDefaultGrossPrice())
            ->setCurrency($this->currencyFacade->getByIdCurrency($shipmentMethodPriceEntity->getFkCurrency()))
            ->setStore($this->storeFacade->getStoreById($shipmentMethodPriceEntity->getFkStore()));
    }
}
