<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Currency;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Client\Currency\CurrencyClient;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Currency
 * @group CurrencyClientTest
 * Add your own group annotations below this line
 */
class CurrencyClientTest extends Unit
{
    use ContainerHelperTrait;

    /**
     * @var \SprykerTest\Client\Currency\CurrencyClientTester
     */
    protected CurrencyClientTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockStoreClientDependency();
    }

    /**
     * @return void
     */
    public function testFromIsoCodeReturnsCurrencyTransfer(): void
    {
        // Assign
        $currencyClient = new CurrencyClient();

        // Act
        $currencyTransfer = $currencyClient->fromIsoCode($this->tester::CURRENCY_USD);

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
        $this->assertEquals($this->tester::CURRENCY_USD, $currencyTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testGetCurrentReturnsDefaultCurency(): void
    {
        // Assign
        $currencyClient = new CurrencyClient();

        // Act
        $currencyTransfer = $currencyClient->getCurrent();

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
        $this->assertEquals($this->tester::CURRENCY_USD, $currencyTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testSetCurrentCurrencyIsoCodeChecksCorrectCurencyIsoCode(): void
    {
        // Assign
        $currencyClient = new CurrencyClient();
        $currencyClient->setCurrentCurrencyIsoCode($this->tester::CURRENCY_EUR);

        // Act
        $currencyTransfer = $currencyClient->getCurrent();

        // Assert
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
        $this->assertEquals($this->tester::CURRENCY_EUR, $currencyTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testGetCurrencyIsoCodesReturnsAvailbleCurrencyIsoCodes(): void
    {
        // Assign
        $currencyClient = new CurrencyClient();
        $currencyClient->setCurrentCurrencyIsoCode($this->tester::CURRENCY_EUR);

        // Act
        $currencyIsoCodes = $currencyClient->getCurrencyIsoCodes();

        // Assert
        $this->assertSame([$this->tester::CURRENCY_EUR, $this->tester::CURRENCY_USD], $currencyIsoCodes);
    }
}
