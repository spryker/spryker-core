<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Formatter\IntlMoneyFormatter;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Converter\TransferToMoneyConverterInterface;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Formatter
 * @group IntlMoneyFormatterWithCurrency
 */
class IntlMoneyFormatterWithCurrencyTest extends \PHPUnit_Framework_TestCase
{

    const AMOUNT = '1000';
    const CURRENCY = 'EUR';
    const LOCALE = 'de_DE';

    /**
     * @return void
     */
    public function testConstruct()
    {
        $intlMoneyFormatter = new IntlMoneyFormatterWithCurrency($this->getTransferToMoneyConverterMock());
        $this->assertInstanceOf(MoneyFormatterInterface::class, $intlMoneyFormatter);
    }

    /**
     * @return void
     */
    public function testFormatShouldReturnFormatted()
    {
        $intlMoneyFormatter = new IntlMoneyFormatterWithCurrency($this->getTransferToMoneyConverterMock());
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount(self::AMOUNT);
        $moneyTransfer->setCurrency(self::CURRENCY);
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(self::LOCALE);
        $moneyTransfer->setLocale($localeTransfer);

        $formatted = $intlMoneyFormatter->format($moneyTransfer);
        $this->assertSame('10,00 €', $formatted);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Money\Converter\TransferToMoneyConverterInterface
     */
    protected function getTransferToMoneyConverterMock()
    {
        $transferToMoneyConverterMock = $this->getMockBuilder(TransferToMoneyConverterInterface::class)->getMock();
        $transferToMoneyConverterMock->method('convert')->willReturnCallback([$this, 'convert']);

        return $transferToMoneyConverterMock;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return \Money\Money
     */
    public function convert(MoneyTransfer $moneyTransfer)
    {
        $money = new Money($moneyTransfer->getAmount(), new Currency($moneyTransfer->getCurrency()));

        return $money;
    }

}
