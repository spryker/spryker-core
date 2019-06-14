<?php

namespace Spryker\Zed\PriceProduct\Persistence\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;

class PriceProductMapper
{
    public function mapPriceProductStoreEntityToPriceProductTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {

        $priceProductEntity = $priceProductStoreEntity->getPriceProduct();
        $priceProductStoreEntityData = $priceProductStoreEntity->toArray();

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setName($priceProductEntity->getPriceType()->getName())
            ->setPriceModeConfiguration($priceProductEntity->getPriceType()->getPriceModeConfiguration());

        $currencyTransfer = (new CurrencyTransfer())->fromArray($priceProductStoreEntity->getCurrency()->toArray(), true);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->fromArray($priceProductStoreEntityData, true)
            ->setIdEntity($priceProductStoreEntity->getIdPriceProductStore())
            ->setNetAmount($priceProductStoreEntity->getNetPrice())
            ->setGrossAmount($priceProductStoreEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);

        $priceProductDimensionTransfer = new PriceProductDimensionTransfer();
        $priceProductDimensionTransfer->fromArray($priceProductStoreEntityData, true);

        $sku = $priceProductEntity->getProduct() ? $priceProductEntity->getProduct()->getSku() : $priceProductStoreEntityData['product_sku'];
        return $priceProductTransfer
            ->fromArray($priceProductStoreEntityData, true)
            ->setSkuProduct($sku)
            ->setIdProduct($priceProductEntity->getFkProduct())
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceType($priceTypeTransfer)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer)
            ->setIsMergeable(true);
    }
}
