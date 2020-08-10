<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class PriceProductOfferMapper
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     *
     * @return \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer
     */
    public function mapPriceProductTransferToPriceProductOfferEntity(
        PriceProductTransfer $priceProductTransfer,
        SpyPriceProductOffer $priceProductOfferEntity
    ): SpyPriceProductOffer {
        $priceProductOfferEntity->setFkProductOffer($priceProductTransfer->getPriceDimension()->getIdProductOffer());
        $priceProductOfferEntity->setFkPriceProductStore((string)$priceProductTransfer->getMoneyValue()->getIdEntity());

        return $priceProductOfferEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $priceProductOfferEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductOfferEntitiesToPriceProductTransfers(
        ObjectCollection $priceProductOfferEntities,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($priceProductOfferEntities as $priceProductOfferEntity) {
            $priceProductTransfer = $this->mapPriceProductOfferEntityToPriceProductTransfer(
                $priceProductOfferEntity,
                new PriceProductTransfer()
            );
            $priceProductTransfers->append($priceProductTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferEntityToPriceProductTransfer(
        SpyPriceProductOffer $priceProductOfferEntity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer = $this->mapPriceProductStoreEntityToPriceProductTransfer(
            $priceProductOfferEntity->getSpyPriceProductStore(),
            $priceProductTransfer
        );
        $priceProductTransfer->setPriceDimension(
            (new PriceProductDimensionTransfer())
                ->setIdProductOffer($priceProductOfferEntity->getFkProductOffer())
                ->setIdPriceProductOffer((int)$priceProductOfferEntity->getIdPriceProductOffer())
        );

        return $priceProductTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductStoreEntityToPriceProductTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer->setIdPriceProduct($priceProductStoreEntity->getFkPriceProduct());
        $priceProductTransfer = $priceProductTransfer->setMoneyValue(
            $this->mapPriceProductStoreEntityToMoneyValueTransfer(
                $priceProductStoreEntity,
                new MoneyValueTransfer()
            )
        );
        $priceProductTransfer->setPriceType(
            $this->mapPriceTypeEntityToPriceTypeTransfer(
                $priceProductStoreEntity->getPriceProduct()->getPriceType(),
                new PriceTypeTransfer()
            )
        );

        return $priceProductTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapPriceProductStoreEntityToMoneyValueTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $moneyValueTransfer->fromArray($priceProductStoreEntity->toArray(), true);
        $moneyValueTransfer->setIdEntity((int)$priceProductStoreEntity->getIdPriceProductStore());
        $moneyValueTransfer->setGrossAmount($priceProductStoreEntity->getGrossPrice());
        $moneyValueTransfer->setNetAmount($priceProductStoreEntity->getNetPrice());
        $moneyValueTransfer->setCurrency(
            $this->mapCurrencyEntityToTransfer($priceProductStoreEntity->getCurrency(), new CurrencyTransfer())
        );
        $moneyValueTransfer->setStore(
            $this->mapStoreEntityToStoreTransfer($priceProductStoreEntity->getStore(), new StoreTransfer())
        );

        return $moneyValueTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function mapPriceTypeEntityToPriceTypeTransfer(
        SpyPriceType $priceTypeEntity,
        PriceTypeTransfer $priceTypeTransfer
    ): PriceTypeTransfer {
        return $priceTypeTransfer->fromArray($priceTypeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function mapCurrencyEntityToTransfer(SpyCurrency $currencyEntity, CurrencyTransfer $currencyTransfer): CurrencyTransfer
    {
        return $currencyTransfer->fromArray($currencyEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
