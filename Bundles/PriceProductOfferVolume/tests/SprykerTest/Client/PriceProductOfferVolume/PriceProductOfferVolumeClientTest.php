<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductOfferVolume;

use Codeception\Test\Unit;

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
    /**
     * @var \SprykerTest\Client\PriceProductOfferVolume\PriceProductOfferVolumeTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractProductPricesForProductOffer(): void
    {
        // Arrange
        $priceProductTransfers = $this->tester->preparePriceProductsWithVolumePrices();

        // Act
        $priceProductTransfers = $this->tester->getClient()->extractProductPrices($priceProductTransfers);

        // Assert
        $this->assertGreaterThan(1, count($priceProductTransfers));
    }
}
