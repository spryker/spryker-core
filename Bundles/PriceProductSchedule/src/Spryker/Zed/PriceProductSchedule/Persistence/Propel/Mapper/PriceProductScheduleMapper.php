<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductScheduleMapper implements PriceProductScheduleMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface
     */
    protected $priceProductScheduleListMapper;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $config
     */
    public function __construct(
        PriceProductScheduleListMapperInterface $priceProductScheduleListMapper,
        PriceProductScheduleConfig $config
    ) {
        $this->priceProductScheduleListMapper = $priceProductScheduleListMapper;
        $this->config = $config;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleEntityToPriceProductTransfer(
            $priceProductScheduleEntity,
            new PriceProductTransfer()
        );

        $priceProductScheduleListTransfer = $this->priceProductScheduleListMapper
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleEntity->getPriceProductScheduleList(),
                new PriceProductScheduleListTransfer()
            );

        $storeTransfer = $this->mapStoreEntityToStoreTransfer(
            $priceProductScheduleEntity->getStore(),
            new StoreTransfer()
        );

        return $priceProductScheduleTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setStore($storeTransfer)
            ->setPriceProduct($priceProductTransfer)
            ->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule
     */
    public function mapPriceProductScheduleTransferToPriceProductScheduleEntity(
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): SpyPriceProductSchedule {
        $priceProductScheduleEntity->fromArray($priceProductScheduleTransfer->toArray());
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        if ($priceProductTransfer === null) {
            return $priceProductScheduleEntity;
        }

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        if ($moneyValueTransfer === null) {
            return $priceProductScheduleEntity;
        }

        $idStore = $moneyValueTransfer->getFkStore();
        $idCurrency = $moneyValueTransfer->getFkCurrency();

        if ($priceProductTransfer->getIdProductAbstract() !== null) {
            $priceProductScheduleEntity->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        }

        if ($priceProductTransfer->getIdProduct() !== null) {
            $priceProductScheduleEntity->setFkProduct($priceProductTransfer->getIdProduct());
        }

        if ($idStore === null && $moneyValueTransfer->getStore() !== null) {
            $idStore = $moneyValueTransfer->getStore()->getIdStore();
        }

        if ($idCurrency === null && $moneyValueTransfer->getCurrency() !== null) {
            $idCurrency = $moneyValueTransfer->getCurrency()->getIdCurrency();
        }

        $idPriceProductScheduleList = null;
        $priceProductScheduleListTransfer = $priceProductScheduleTransfer->getPriceProductScheduleList();
        if ($priceProductScheduleListTransfer !== null) {
            $idPriceProductScheduleList = (string)$priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        }

        return $priceProductScheduleEntity
            ->setFkCurrency($idCurrency)
            ->setFkStore($idStore)
            ->setFkPriceType($priceProductTransfer->getPriceType()->getIdPriceType())
            ->setFkPriceProductScheduleList($idPriceProductScheduleList)
            ->setNetPrice($moneyValueTransfer->getNetAmount())
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->setActiveFrom($priceProductScheduleTransfer->getActiveFrom())
            ->setActiveTo($priceProductScheduleTransfer->getActiveTo())
            ->setIsCurrent($priceProductScheduleTransfer->getIsCurrent());
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[] $priceProductScheduleEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers(
        array $priceProductScheduleEntities
    ): array {
        $productPriceScheduleCollection = [];

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $productPriceScheduleCollection[] = $this->mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
                $priceProductScheduleEntity,
                new PriceProductScheduleTransfer()
            );
        }

        return $productPriceScheduleCollection;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = $this->mapPriceProductScheduleEntityToMoneyValueTransfer(
            $priceProductScheduleEntity,
            new MoneyValueTransfer()
        );

        $priceTypeTransfer = $this->mapPriceTypeEntityToPriceTypeTransfer(
            $priceProductScheduleEntity->getPriceType(),
            new PriceTypeTransfer()
        );

        $priceProductDimensionTransfer = $this->mapPriceProductScheduleEntityToPriceProductDimensionTransfer(
            $priceProductScheduleEntity,
            new PriceProductDimensionTransfer()
        );

        $priceProductTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer);

        if ($priceProductScheduleEntity->getFkProduct()) {
            $productConcreteEntity = $priceProductScheduleEntity->getProduct();

            $priceProductTransfer->setIdProduct($productConcreteEntity->getIdProduct());
            $priceProductTransfer->setSkuProduct($productConcreteEntity->getSku());
        }

        if ($priceProductScheduleEntity->getFkProductAbstract()) {
            $productAbstractEntity = $priceProductScheduleEntity->getProductAbstract();

            $priceProductTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
            $priceProductTransfer->setSkuProductAbstract($productAbstractEntity->getSku());
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapPriceProductScheduleEntityToMoneyValueTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $currencyTransfer = $this->mapCurrencyEntityToCurrencyTransfer(
            $priceProductScheduleEntity->getCurrency(),
            new CurrencyTransfer()
        );

        $storeTransfer = $this->mapStoreEntityToStoreTransfer(
            $priceProductScheduleEntity->getStore(),
            new StoreTransfer()
        );

        return $moneyValueTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setNetAmount($priceProductScheduleEntity->getNetPrice())
            ->setGrossAmount($priceProductScheduleEntity->getGrossPrice())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function mapCurrencyEntityToCurrencyTransfer(
        SpyCurrency $currencyEntity,
        CurrencyTransfer $currencyTransfer
    ): CurrencyTransfer {
        return $currencyTransfer
            ->fromArray($currencyEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(
        SpyStore $storeEntity,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType $spyPriceType
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function mapPriceTypeEntityToPriceTypeTransfer(
        SpyPriceType $spyPriceType,
        PriceTypeTransfer $priceTypeTransfer
    ): PriceTypeTransfer {
        return $priceTypeTransfer
            ->fromArray($spyPriceType->toArray(), true);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductDimensionTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductDimensionTransfer $priceProductDimensionTransfer
    ): PriceProductDimensionTransfer {
        return $priceProductDimensionTransfer
            ->fromArray($priceProductScheduleEntity->getVirtualColumns(), true)
            ->setType($this->config->getPriceDimensionDefault());
    }
}
