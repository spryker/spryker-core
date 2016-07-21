<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Functional\Spryker\Zed\Money\Communication\Plugin;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;
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
    const CURRENCY_EUR = self::CURRENCY;
    const LOCALE_DE_DE = self::LOCALE;
    const LOCALE_EN_US = 'en_US';
    const CURRENCY = 'EUR';
    const LOCALE = 'de_DE';

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransfer()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->getMoney(self::AMOUNT_INTEGER);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithConfiguredDefaultCurrency()
    {
        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->getMoney(self::AMOUNT_INTEGER);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());

        $defaultCurrency = Store::getInstance()->getCurrencyIsoCode();
        $this->assertSame($defaultCurrency, $moneyTransfer->getCurrency());
    }

    /**
     * @return void
     */
    public function testGetMoneyShouldReturnMoneyTransferWithPassedCurrency()
    {
        $moneyPlugin = new MoneyPlugin();

        $currency = 'USD';
        $moneyTransfer = $moneyPlugin->getMoney(self::AMOUNT_INTEGER, $currency);
        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());

        $this->assertSame($currency, $moneyTransfer->getCurrency());
    }

    /**
     * @return void
     */
    public function testFormatWithSymbolShouldReturnFormattedStringWithCurrencySymbol()
    {
        Store::getInstance()->setCurrentLocale(self::LOCALE);

        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->getMoney(self::AMOUNT_INTEGER, self::CURRENCY);

        $this->assertSame('10,00 €', $moneyPlugin->formatWithSymbol($moneyTransfer));
    }

    /**
     * @return void
     */
    public function testFormatWithoutSymbolShouldReturnFormattedStringWithoutCurrencySymbol()
    {
        Store::getInstance()->setCurrentLocale(self::LOCALE);

        $moneyPlugin = new MoneyPlugin();
        $moneyTransfer = $moneyPlugin->getMoney(self::AMOUNT_INTEGER, self::CURRENCY);

        $this->assertSame('10,00', $moneyPlugin->formatWithoutSymbol($moneyTransfer));
    }

}
