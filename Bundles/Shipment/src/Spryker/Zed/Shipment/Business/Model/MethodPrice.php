<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class MethodPrice implements MethodPriceInterface
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return void
     */
    public function save(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $existingPriceEntityMap = $this->getShipmentMethodPriceMap($shipmentMethodTransfer->getIdShipmentMethod());
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            $priceEntity = $this->findExistingShipmentMethodPrice($existingPriceEntityMap, $moneyValueTransfer);
            if ($priceEntity !== null) {
                $this->updatePriceEntity($priceEntity, $moneyValueTransfer);

                continue;
            }

            $this->createPriceEntity($moneyValueTransfer, $shipmentMethodTransfer->getIdShipmentMethod());
        }
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return array
     */
    protected function getShipmentMethodPriceMap($idShipmentMethod)
    {
        $priceEntityCollection = $this->queryContainer->queryMethodPricesByIdShipmentMethod($idShipmentMethod)->find();

        $priceEntityMap = [];
        foreach ($priceEntityCollection as $priceEntity) {
            $priceEntityMap[$priceEntity->getFkStore()][$priceEntity->getFkCurrency()] = $priceEntity;
        }

        return $priceEntityMap;
    }

    /**
     * @param array $priceEntityMap
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice|null
     */
    protected function findExistingShipmentMethodPrice(array $priceEntityMap, MoneyValueTransfer $moneyValueTransfer)
    {
        if (!isset($priceEntityMap[$moneyValueTransfer->getFkStore()])) {
            return null;
        }

        if (!isset($priceEntityMap[$moneyValueTransfer->getFkStore()][$moneyValueTransfer->getFkCurrency()])) {
            return null;
        }

        return $priceEntityMap[$moneyValueTransfer->getFkStore()][$moneyValueTransfer->getFkCurrency()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $priceEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return void
     */
    protected function updatePriceEntity(SpyShipmentMethodPrice $priceEntity, MoneyValueTransfer $moneyValueTransfer)
    {
        $priceEntity->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
        $priceEntity->setDefaultNetPrice($moneyValueTransfer->getNetAmount());
        $priceEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param int $idShipmentMethod
     *
     * @return void
     */
    protected function createPriceEntity(MoneyValueTransfer $moneyValueTransfer, $idShipmentMethod)
    {
        $priceEntity = new SpyShipmentMethodPrice();
        $priceEntity->fromArray($moneyValueTransfer->toArray());
        $priceEntity->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
        $priceEntity->setDefaultNetPrice($moneyValueTransfer->getNetAmount());
        $priceEntity->setFkShipmentMethod($idShipmentMethod);
        $priceEntity->save();
    }

}
