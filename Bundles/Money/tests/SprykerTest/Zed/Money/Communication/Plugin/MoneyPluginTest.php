<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Money\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Money\Communication\Plugin\MoneyPlugin;

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
    public const AMOUNT_INTEGER = 1000;
    public const AMOUNT_FLOAT = 10.00;
    public const AMOUNT_STRING = '1000';

    public const CURRENCY_EUR = 'EUR';
    public const LOCALE_DE_DE = 'de_DE';
    public const LOCALE_EN_US = 'en_US';

    /**
     * @return void
     */
    public function testFromIntegerShouldReturnMoneyTransfer()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testFromFloatShouldReturnMoneyTransfer()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromFloat(self::AMOUNT_FLOAT);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testFromStringShouldReturnMoneyTransfer()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromString(self::AMOUNT_STRING);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithConfiguredDefaultCurrency()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());

        $defaultCurrency = Store::getInstance()->getCurrencyIsoCode();
        $this->assertSame($defaultCurrency, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithPassedCurrency()
    {
        $moneyPlugin = new MoneyPlugin();

        $isoCode = 'USD';
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER, $isoCode);
        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());

        $this->assertSame($isoCode, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFormatWithSymbolShouldReturnFormattedStringWithCurrencySymbol()
    {
        Store::getInstance()->setCurrentLocale(self::LOCALE_DE_DE);

        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER, self::CURRENCY_EUR);

        $this->assertSame('10,00 €', $moneyPlugin->formatWithSymbol($moneyTransfer));
    }

    /**
     * @return void
     */
    public function testFormatWithoutSymbolShouldReturnFormattedStringWithoutCurrencySymbol()
    {
        Store::getInstance()->setCurrentLocale(self::LOCALE_DE_DE);

        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER, self::CURRENCY_EUR);

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
    public function testParseShouldReturnMoneyTransfer($value, $isoCode, $expectedAmount)
    {
        $moneyPlugin = new MoneyPlugin();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($isoCode);

        $this->assertSame($expectedAmount, $moneyPlugin->parse($value, $currencyTransfer)->getAmount());
    }

    /**
     * @return array
     */
    public function parseData()
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
    public function testConvertIntegerToDecimalShouldReturnFloat()
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertIntegerToDecimal(1000);
        $this->assertIsFloat($converted);
        $this->assertSame(10.00, $converted);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToIntegerShouldReturnInt()
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertDecimalToInteger(10.00);
        $this->assertIsInt($converted);
        $this->assertSame(1000, $converted);
    }
}
