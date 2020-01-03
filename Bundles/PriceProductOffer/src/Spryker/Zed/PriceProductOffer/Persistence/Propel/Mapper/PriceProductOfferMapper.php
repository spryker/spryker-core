<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;

class PriceProductOfferMapper implements PriceProductOfferMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferEntityToPriceProductTransfer(SpyPriceProductOffer $priceProductOfferEntity, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer
            ->setSkuProduct($priceProductOfferEntity->getSpyProductOffer()->getConcreteSku())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER)
                    ->setProductOfferReference($priceProductOfferEntity->getSpyProductOffer()->getProductOfferReference())
            )
            ->setIsMergeable(false)
            ->setPriceTypeName($priceProductOfferEntity->getSpyPriceType()->getName())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkStore($priceProductOfferEntity->getFkStore())
                    ->setCurrency(
                        (new CurrencyTransfer())->fromArray($priceProductOfferEntity->getSpyCurrency()->toArray(), true)
                    )
                    ->setNetAmount($priceProductOfferEntity->getNetPrice())
                    ->setGrossAmount($priceProductOfferEntity->getGrossPrice())
            );

        return $priceProductTransfer;
    }
}
