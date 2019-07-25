<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Price;

use Codeception\Test\Unit;
use Spryker\Client\Price\PriceConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Price
 * @group PriceClientTest
 * Add your own group annotations below this line
 */
class PriceClientTest extends Unit
{
    protected const CACHED_PRICE_MODE = 'CACHED_PRICE_MODE';

    /**
     * @var \SprykerTest\Client\Price\PriceClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->createPriceModeCache()->invalidate();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tester->createPriceModeCache()->invalidate();
    }

    /**
     * @return void
     */
    public function testGetCurrentPriceModeReturnsDefaultPriceMode(): void
    {
        //Arrange
        $priceClient = $this->tester->getLocator()->price()->client();

        //Act
        $priceMode = $priceClient->getCurrentPriceMode();

        //Assert
        $this->assertEquals($this->createPriceConfig()->getDefaultPriceMode(), $priceMode);
    }

    /**
     * @return void
     */
    public function testGetCurrentPriceModeReturnsCachedPriceMode(): void
    {
        //Arrange
        $priceClient = $this->tester->getLocator()->price()->client();
        $this->tester->createPriceModeCache()->cache(static::CACHED_PRICE_MODE);

        //Act
        $priceMode = $priceClient->getCurrentPriceMode();

        //Assert
        $this->assertEquals(static::CACHED_PRICE_MODE, $priceMode);
    }

    /**
     * @return void
     */
    public function testSwitchPriceModeSwitchPriceModeInCache(): void
    {
        //Arrange
        $priceClient = $this->tester->getLocator()->price()->client();
        $this->tester->createPriceModeCache()->cache(static::CACHED_PRICE_MODE);
        $defaultPriceMode = $this->createPriceConfig()->getDefaultPriceMode();

        //Act
        $priceClient->switchPriceMode($defaultPriceMode);
        $priceModeCache = $this->tester->createPriceModeCache()->get();

        //Assert
        $this->assertNotNull($priceModeCache);
        $this->assertEquals($defaultPriceMode, $priceModeCache);
    }

    /**
     * @return \Spryker\Client\Price\PriceConfig
     */
    protected function createPriceConfig(): PriceConfig
    {
        return new PriceConfig();
    }
}
