<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class ShipmentMethodMapper implements ShipmentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethodEntity
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    public function mapShipmentMethodTransferToShipmentMethodEntity(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        SpyShipmentMethod $salesShipmentMethodEntity
    ): SpyShipmentMethod {
        $salesShipmentMethodEntity->fromArray($shipmentMethodTransfer->modifiedToArray());

        return $salesShipmentMethodEntity;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethodEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentMethodEntityToShipmentMethodTransfer(
        SpyShipmentMethod $salesShipmentMethodEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): ShipmentMethodTransfer {
        $shipmentMethodTransfer = $shipmentMethodTransfer->fromArray($salesShipmentMethodEntity->toArray(), true);

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethodEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
        SpyShipmentMethod $salesShipmentMethodEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): ShipmentMethodTransfer {
        $shipmentMethodTransfer = $shipmentMethodTransfer->fromArray($salesShipmentMethodEntity->toArray(), true);
        $shipmentMethodTransfer->setCarrierName($salesShipmentMethodEntity->getShipmentCarrier()->getName());
        $shipmentMethodTransfer->setPrices($this->getPriceCollection($salesShipmentMethodEntity));
        $shipmentMethodTransfer->setStoreRelation(
            $this->mapShipmentMethodStoreEntitiesToStoreRelationTransfer(
                $salesShipmentMethodEntity->getShipmentMethodStores(),
                new StoreRelationTransfer()
            )
        );

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore[] $shipmentMethodStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapShipmentMethodStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $shipmentMethodStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($shipmentMethodStoreEntities as $shipmentMethodStoreEntity) {
            $storeRelationTransfer->addStore($this->mapStoreEntityToStoreTransfer($shipmentMethodStoreEntity->getStore(), new StoreTransfer()));
            $storeRelationTransfer->addIdStores($shipmentMethodStoreEntity->getFkStore());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function mapCurrencyEntityToCurrencyTransfer(
        SpyCurrency $currencyEntity,
        CurrencyTransfer $currencyTransfer
    ): CurrencyTransfer {
        return $currencyTransfer->fromArray($currencyEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function mapStoreEntityToStoreTransfer(
        SpyStore $storeEntity,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $shipmentMethodPrice
     * @param \Generated\Shared\Transfer\ShipmentPriceTransfer $shipmentPriceTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentPriceTransfer
     */
    public function mapShipmentMethodPriceEntityToShipmentPriceTransfer(
        SpyShipmentMethodPrice $shipmentMethodPrice,
        ShipmentPriceTransfer $shipmentPriceTransfer
    ): ShipmentPriceTransfer {
        return $shipmentPriceTransfer->fromArray($shipmentMethodPrice->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice $shipmentMethodPriceEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function mapShipmentMethodPriceEntityToMoneyValueTransfer(
        SpyShipmentMethodPrice $shipmentMethodPriceEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $moneyValueTransfer = $moneyValueTransfer->fromArray($shipmentMethodPriceEntity->toArray(), true);
        $moneyValueTransfer
            ->setIdEntity($shipmentMethodPriceEntity->getIdShipmentMethodPrice())
            ->setNetAmount($shipmentMethodPriceEntity->getDefaultNetPrice())
            ->setGrossAmount($shipmentMethodPriceEntity->getDefaultGrossPrice());

        $currencyTransfer = $this->mapCurrencyEntityToCurrencyTransfer(
            $shipmentMethodPriceEntity->getCurrency(),
            new CurrencyTransfer()
        );
        $moneyValueTransfer->setCurrency($currencyTransfer);

        $storeTransfer = $this->mapStoreEntityToStoreTransfer(
            $shipmentMethodPriceEntity->getStore(),
            new StoreTransfer()
        );
        $moneyValueTransfer->setStore($storeTransfer);

        return $moneyValueTransfer;
    }

    /**
     * @param iterable|\Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]|\Propel\Runtime\Collection\ObjectCollection $shipmentMethodsEntities
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function mapShipmentMethodEntitiesToShipmentMethodTransfers(
        iterable $shipmentMethodsEntities,
        array $shipmentMethodTransfers
    ): array {
        foreach ($shipmentMethodsEntities as $salesShipmentMethodEntity) {
            $shipmentMethodTransfers[] = $this->mapShipmentMethodEntityToShipmentMethodTransfer(
                $salesShipmentMethodEntity,
                new ShipmentMethodTransfer()
            );
        }

        return $shipmentMethodTransfers;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethodEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    protected function getPriceCollection(SpyShipmentMethod $salesShipmentMethodEntity): ArrayObject
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($salesShipmentMethodEntity->getShipmentMethodPrices() as $shipmentMethodPriceEntity) {
            $moneyValueTransfer = $this->mapShipmentMethodPriceEntityToMoneyValueTransfer(
                $shipmentMethodPriceEntity,
                new MoneyValueTransfer()
            );

            $moneyValueCollection->append($moneyValueTransfer);
        }

        return $moneyValueCollection;
    }
}
