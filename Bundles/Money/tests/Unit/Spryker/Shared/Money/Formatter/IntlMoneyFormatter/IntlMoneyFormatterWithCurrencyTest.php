<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Formatter\IntlMoneyFormatter;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;

/**
 * @group IntlMoneyFormatterWithCurrency
 */
class IntlMoneyFormatterWithCurrencyTest extends AbstractIntlMoneyFormatterTest
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

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode(self::CURRENCY);
        $moneyTransfer->setCurrency($currencyTransfer);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(self::LOCALE);
        $moneyTransfer->setLocale($localeTransfer);

        $formatted = $intlMoneyFormatter->format($moneyTransfer);
        $this->assertSame('10,00 €', $formatted);
    }

}
