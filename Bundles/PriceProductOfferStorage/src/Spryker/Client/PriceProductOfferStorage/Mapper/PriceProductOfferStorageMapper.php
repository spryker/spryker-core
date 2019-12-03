<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferStorageTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;

class PriceProductOfferStorageMapper implements PriceProductOfferStorageMapperInterface
{
    /**
     * @param array $priceProductOffer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferStorageDataToPriceProductTransfer(array $priceProductOffer, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductOfferStorageTransfer = (new PriceProductOfferStorageTransfer())->fromArray($priceProductOffer);

        $priceProductTransfer
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductOfferStorageConfig::DIMENSION_TYPE_PRODUCT_OFFER)
                    ->setProductOfferReference($priceProductOfferStorageTransfer->getProductOfferReference())
            )
            ->setIsMergeable(false)
            ->setPriceTypeName($priceProductOfferStorageTransfer->getPriceType())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency(
                        (new CurrencyTransfer())->setCode($priceProductOfferStorageTransfer->getCurrency())
                    )
                    ->setNetAmount($priceProductOfferStorageTransfer->getNetPrice())
                    ->setGrossAmount($priceProductOfferStorageTransfer->getGrossPrice())
            );

        return $priceProductTransfer;
    }
}
