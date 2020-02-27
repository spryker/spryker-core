<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductOfferVolume;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductOfferVolume
 * @group PriceProductOfferVolumeClientTest
 * Add your own group annotations below this line
 */
class PriceProductOfferVolumeClientTest extends Unit
{
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const MONEY_VALUE = 10000;
    protected const PRICE_DIMENSION_DEFAULT = 'PRODUCT_OFFER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \SprykerTest\Zed\PriceProductOfferVolume\PriceProductOfferVolumeTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractProductPricesForProductOffer(): void
    {
        $priceProductTransfers = $this->preparePriceProductsWithVolumePrices();

        $offerVolumePrices = $this->tester->getClient()->extractProductPricesForProductOffer($priceProductTransfers);

        $this->assertGreaterThan(1, count($offerVolumePrices));
    }

    /**
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        int $netPrice,
        int $grossPrice
    ): PriceProductTransfer {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_DEFAULT);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        int $grossAmount,
        int $netAmount
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function preparePriceProductsWithVolumePrices(): array
    {
        $priceProductTransfer = $this->createPriceProductTransfer(static::MONEY_VALUE, static::MONEY_VALUE);
        $priceProductTransfer->getMoneyValue()->setPriceData(static::PRICE_DATA_VOLUME);

        return [$priceProductTransfer];
    }
}
