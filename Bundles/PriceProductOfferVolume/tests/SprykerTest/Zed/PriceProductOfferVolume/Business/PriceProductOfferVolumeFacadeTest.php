<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferVolume\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOfferVolume
 * @group Business
 * @group Facade
 * @group PriceProductOfferVolumeFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductOfferVolumeFacadeTest extends Unit
{
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const MONEY_VALUE = 10000;
    protected const PRICE_DIMENSION_TYPE = 'PRODUCT_OFFER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \SprykerTest\Zed\PriceProductOfferVolume\PriceProductOfferVolumeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractVolumePrices(): void
    {
        // Arrange
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount(static::MONEY_VALUE)
            ->setGrossAmount(static::MONEY_VALUE)
            ->setPriceData(static::PRICE_DATA_VOLUME);

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        // Act
        $volumePrices = $this->tester
            ->getFacade()
            ->extractVolumePrices([$priceProductTransfer]);

        // Assert
        $this->assertCount(2, $volumePrices);
    }
}
