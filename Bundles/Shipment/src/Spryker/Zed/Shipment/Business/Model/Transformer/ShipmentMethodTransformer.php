<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentMethodTransformer implements ShipmentMethodTransformerInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var array Keys are currency iso codes, values are Currency transfer object data in array format.
     */
    protected static $currencyCache = [];

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentToCurrencyInterface $currencyFacade, ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->currencyFacade = $currencyFacade;
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function transformEntityToTransfer(SpyShipmentMethod $shipmentMethodEntity)
    {
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->fromArray($shipmentMethodEntity->toArray(), true)
            ->setCarrierName($this->findShipmentCarrierName($shipmentMethodEntity))
            ->setPrices($this->getPriceCollection($shipmentMethodEntity));

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $methodEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    protected function getPriceCollection(SpyShipmentMethod $methodEntity)
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($methodEntity->getShipmentMethodPrices() as $shipmentMethodPriceEntity) {
            $moneyValueTransfer = (new MoneyValueTransfer())
                ->fromArray($shipmentMethodPriceEntity->toArray(), true)
                ->setIdEntity($shipmentMethodPriceEntity->getIdShipmentMethodPrice())
                ->setNetAmount($shipmentMethodPriceEntity->getDefaultNetPrice())
                ->setGrossAmount($shipmentMethodPriceEntity->getDefaultGrossPrice())
                ->setCurrency($this->getCurrencyTransfer($shipmentMethodPriceEntity->getFkCurrency()));
            $moneyValueCollection->append($moneyValueTransfer);
        }

        return $moneyValueCollection;
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer($idCurrency)
    {
        if (isset(static::$currencyCache[$idCurrency])) {
            return (new CurrencyTransfer())->fromArray(static::$currencyCache[$idCurrency]);
        }

        $currencyTransfer = $this->currencyFacade->getByIdCurrency($idCurrency);
        static::$currencyCache[$idCurrency] = $currencyTransfer->toArray();

        return $currencyTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return string|null
     */
    protected function findShipmentCarrierName(SpyShipmentMethod $shipmentMethodEntity)
    {
        /** @var \Orm\Zed\Shipment\Persistence\SpyShipmentCarrier|null $shipmentCarrierEntity */
        $shipmentCarrierEntity = $shipmentMethodEntity->getShipmentCarrier();
        if (!$shipmentCarrierEntity) {
            return null;
        }

        return $shipmentCarrierEntity->getName();
    }
}
