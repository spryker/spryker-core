<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Price;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Price\PriceConfig;
use Spryker\Client\Price\PriceDependencyProvider;
use Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Price
 * @group PriceClientTest
 * Add your own group annotations below this line
 */
class PriceClientTest extends Unit
{
    /**
     * @var string
     */
    protected const CACHED_PRICE_MODE = 'CACHED_PRICE_MODE';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

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
        $this->setupSession();

        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
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
        $this->assertSame($this->createPriceConfig()->getDefaultPriceMode(), $priceMode);
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
        $this->assertSame(static::CACHED_PRICE_MODE, $priceMode);
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
        $this->assertSame($defaultPriceMode, $priceModeCache);
    }

    /**
     * @return void
     */
    public function testSwitchPriceModeExecutesCurrentPriceModePreCheckPlugins(): void
    {
        // Arrange
        $defaultPriceMode = $this->createPriceConfig()->getDefaultPriceMode();
        $this->tester->setDependency(PriceDependencyProvider::PLUGINS_CURRENT_PRICE_MODE_PRE_CHECK, [
            $this->getCurrentPriceModePreCheckPluginMock(),
        ]);

        // Act
        $this->tester->getLocator()->price()->client()->switchPriceMode($defaultPriceMode);
    }

    /**
     * @return \Spryker\Client\Price\PriceConfig
     */
    protected function createPriceConfig(): PriceConfig
    {
        return new PriceConfig();
    }

    /**
     * @return void
     */
    protected function setupSession(): void
    {
        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeStorageStoreExpanderPluginMock = $this->createMock(StoreExpanderPluginInterface::class);
        $storeStorageStoreExpanderPluginMock->method('expand')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY));

        return $storeStorageStoreExpanderPluginMock;
    }

    /**
     * @return \Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface
     */
    protected function getCurrentPriceModePreCheckPluginMock(): CurrentPriceModePreCheckPluginInterface
    {
        $currentPriceModePreCheckPluginMock = $this->getMockBuilder(CurrentPriceModePreCheckPluginInterface::class)
            ->getMock();

        $currentPriceModePreCheckPluginMock
            ->expects($this->once())
            ->method('isPriceModeChangeAllowed');

        return $currentPriceModePreCheckPluginMock;
    }
}
