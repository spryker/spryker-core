<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Money\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserInterface;
use Spryker\Zed\Money\Communication\Plugin\MoneyPlugin;
use Spryker\Zed\Money\Dependency\Facade\MoneyToLocaleFacadeInterface;
use Spryker\Zed\Money\MoneyDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Money
 * @group Communication
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
     * @var \SprykerTest\Zed\Money\MoneyCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(MoneyDependencyProvider::MONEY_PARSER, $this->createMoneyParser());
        $this->tester->setDependency(MoneyDependencyProvider::FACADE_LOCALE, $this->createLocaleFacadeMock());
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Money\Dependency\Facade\MoneyToLocaleFacadeInterface
     */
    protected function createLocaleFacadeMock(): MoneyToLocaleFacadeInterface
    {
        $localeFacadeMock = $this->createMock(MoneyToLocaleFacadeInterface::class);
        $localeFacadeMock->method('getCurrentLocale')
            ->willReturn(
                (new LocaleTransfer())
                    ->setLocaleName(static::LOCALE_DE_DE),
            );

        return $localeFacadeMock;
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Parser\MoneyToParserInterface
     */
    protected function createMoneyParser(): MoneyToParserInterface
    {
        $numberFormatter = new NumberFormatter(
            static::LOCALE_DE_DE,
            NumberFormatter::CURRENCY,
        );
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, new ISOCurrencies());

        return new MoneyToParserBridge($intlMoneyParser);
    }
}
