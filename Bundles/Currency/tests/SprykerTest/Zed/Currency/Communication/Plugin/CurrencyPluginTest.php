<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Currency\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\Communication\Plugin\CurrencyPlugin;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Currency
 * @group Communication
 * @group Plugin
 * @group CurrencyPluginTest
 * Add your own group annotations below this line
 */
class CurrencyPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\StoreDependencyProvider::STORE_CURRENT
     *
     * @var string
     */
    protected const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var string
     */
    protected const EUR_ISO_CODE = 'EUR';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Currency\CurrencyCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CurrencyDependencyProvider::FACADE_STORE, $this->createCurrencyToStoreFacadeMock());
    }

    /**
     * @return void
     */
    public function testFromIsoCodeShouldReturnCurrencyTransfer(): void
    {
        // Act
        $currencyTransfer = (new CurrencyPlugin())->fromIsoCode(static::EUR_ISO_CODE);

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return void
     */
    public function testGetDefaultShouldReturnCurrencyTransfer(): void
    {
        // Arrange
        $this->tester->setDependency(static::STORE_CURRENT, static::STORE_NAME);
        $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME,
            StoreTransfer::DEFAULT_CURRENCY_ISO_CODE => static::EUR_ISO_CODE,
        ]);

        // Act
        $currencyTransfer = (new CurrencyPlugin())->getCurrent();

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected function createCurrencyToStoreFacadeMock(): CurrencyToStoreFacadeInterface
    {
        $currencyToStoreClientMock = $this->createMock(CurrencyToStoreFacadeInterface::class);
        $currencyToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())
                ->setName(static::STORE_NAME)
                ->setDefaultCurrencyIsoCode(static::EUR_ISO_CODE));

        return $currencyToStoreClientMock;
    }
}
