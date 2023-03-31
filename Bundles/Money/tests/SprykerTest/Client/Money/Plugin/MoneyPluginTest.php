<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Money\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Currency\CurrencyDependencyProvider;
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Client\Locale\LocaleDependencyProvider;
use Spryker\Client\Money\Plugin\MoneyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Money
 * @group Plugin
 * @group MoneyPluginTest
 * Add your own group annotations below this line
 */
class MoneyPluginTest extends Unit
{
    /**
     * @var int
     */
    public const AMOUNT_INTEGER = 1000;

    /**
     * @var float
     */
    public const AMOUNT_FLOAT = 10.00;

    /**
     * @var string
     */
    public const AMOUNT_STRING = '1000';

    /**
     * @var string
     */
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     */
    public const LOCALE_DE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Client\Money\MoneyClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(LocaleDependencyProvider::LOCALE_CURRENT, static::LOCALE_DE_DE);
        $this->tester->setDependency(CurrencyDependencyProvider::CLIENT_STORE, $this->createCurrencyToStoreClientMock());
    }

    /**
     * @return void
     */
    public function testFromIntegerShouldReturnMoneyTransfer(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(static::AMOUNT_INTEGER, static::CURRENCY_EUR);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(static::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testFromFloatShouldReturnMoneyTransfer(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromFloat(static::AMOUNT_FLOAT, static::CURRENCY_EUR);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(static::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testFromStringShouldReturnMoneyTransfer(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromString(static::AMOUNT_STRING, static::CURRENCY_EUR);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(static::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithConfiguredDefaultCurrency(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(static::AMOUNT_INTEGER, static::CURRENCY_EUR);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(static::AMOUNT_STRING, $moneyTransfer->getAmount());

        $this->assertSame(static::CURRENCY_EUR, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithPassedCurrency(): void
    {
        $moneyPlugin = new MoneyPlugin();

        $isoCode = 'USD';
        $moneyTransfer = $moneyPlugin->fromInteger(static::AMOUNT_INTEGER, $isoCode);
        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(static::AMOUNT_STRING, $moneyTransfer->getAmount());

        $this->assertSame($isoCode, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFormatWithSymbolShouldReturnFormattedStringWithCurrencySymbol(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(static::AMOUNT_INTEGER, static::CURRENCY_EUR);

        $this->assertSame('10,00 €', $moneyPlugin->formatWithSymbol($moneyTransfer));
    }

    /**
     * @return void
     */
    public function testFormatWithoutSymbolShouldReturnFormattedStringWithoutCurrencySymbol(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(static::AMOUNT_INTEGER, static::CURRENCY_EUR);

        $this->assertSame('10,00', $moneyPlugin->formatWithoutSymbol($moneyTransfer));
    }

    /**
     * @dataProvider parseData
     *
     * @param string $value
     * @param string $isoCode
     * @param string $expectedAmount
     *
     * @return void
     */
    public function testParseShouldReturnMoneyTransfer(string $value, string $isoCode, string $expectedAmount): void
    {
        $moneyPlugin = new MoneyPlugin();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($isoCode);

        $this->assertSame($expectedAmount, $moneyPlugin->parse($value, $currencyTransfer)->getAmount());
    }

    /**
     * @return array
     */
    public function parseData(): array
    {
        return [
            ['10,00 €', 'EUR', '1000'],
            ['10,99 €', 'EUR', '1099'],
            ['10,999 €', 'EUR', '1100'],
            ['1000 ¥', 'JPY', '1000'],
            ['1099 ¥', 'JPY', '1099'],
        ];
    }

    /**
     * @return void
     */
    public function testConvertIntegerToDecimalShouldReturnFloat(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertIntegerToDecimal(1000);
        $this->assertIsFloat($converted);
        $this->assertSame(10.00, $converted);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToIntegerShouldReturnInt(): void
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertDecimalToInteger(10.00);
        $this->assertIsInt($converted);
        $this->assertSame(1000, $converted);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    protected function createCurrencyToStoreClientMock(): CurrencyToStoreClientInterface
    {
        $currencyToStoreClientMock = $this->createMock(CurrencyToStoreClientInterface::class);
        $currencyToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setDefaultCurrencyIsoCode(static::CURRENCY_EUR));

        return $currencyToStoreClientMock;
    }
}
