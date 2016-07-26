<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Money\Communication\Plugin;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Money\Communication\Plugin\MoneyPlugin;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Money
 * @group MoneyPlugin
 */
class MoneyPluginTest extends \PHPUnit_Framework_TestCase
{

    const AMOUNT_INTEGER = 1000;
    const AMOUNT_FLOAT = 10.00;
    const AMOUNT_STRING = '1000';

    const CURRENCY_EUR = 'EUR';
    const LOCALE_DE_DE = 'de_DE';
    const LOCALE_EN_US = 'en_US';

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

        $currency = 'USD';
        $moneyTransfer = $moneyPlugin->fromInteger(self::AMOUNT_INTEGER, $currency);
        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());

        $this->assertSame($currency, $moneyTransfer->getCurrency()->getCode());
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
     * @return void
     */
    public function testConvertIntegerToDecimalShouldReturnFloat()
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertIntegerToDecimal(1000);
        $this->assertInternalType('float', $converted);
        $this->assertSame(10.00, $converted);
    }

    /**
     * @return void
     */
    public function testConvertDecimalToIntegerShouldReturnInt()
    {
        $moneyPlugin = new MoneyPlugin();
        $converted = $moneyPlugin->convertDecimalToInteger(10.00);
        $this->assertInternalType('int', $converted);
        $this->assertSame(1000, $converted);
    }

}
