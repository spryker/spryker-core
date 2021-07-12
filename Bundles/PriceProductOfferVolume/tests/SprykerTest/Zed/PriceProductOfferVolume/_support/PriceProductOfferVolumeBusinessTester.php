<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Business\PriceProductOfferVolume;

use Codeception\Actor;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductOfferVolumeBusinessTester extends Actor
{
    use _generated\PriceProductOfferVolumeBusinessTesterActions;

    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    public function createValidPriceProductOfferCollection(): PriceProductOfferCollectionTransfer
    {
        $priceProductTransfer1 = (new PriceProductTransfer())
            ->setPriceType((new PriceTypeTransfer())->setName(static::PRICE_TYPE_DEFAULT)->setIdPriceType(1))
            ->setVolumeQuantity(100)
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkStore(1)
                    ->setFkCurrency(1)
                    ->setNetAmount(1)
                    ->setGrossAmount(1)
                    ->setPriceData('{"volume_prices":[{"quantity":100,"net_price":80,"gross_price":100}]}')
            );
        $priceProductTransfer2 = (new PriceProductTransfer())
            ->setPriceType((new PriceTypeTransfer())->setName(static::PRICE_TYPE_DEFAULT)->setIdPriceType(1))
            ->setVolumeQuantity(10)
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkStore(2)
                    ->setFkCurrency(2)
                    ->setNetAmount(1)
                    ->setGrossAmount(1)
                    ->setPriceData('{"volume_prices":[{"quantity":10,"net_price":100,"gross_price":120}]}')
            );
        $priceProductOfferTransfer = (new PriceProductOfferTransfer())
            ->setProductOffer(
                (new ProductOfferTransfer())->addPrice($priceProductTransfer1)->addPrice($priceProductTransfer2)
            );

        return (new PriceProductOfferCollectionTransfer())->addPriceProductOffer($priceProductOfferTransfer);
    }
}
