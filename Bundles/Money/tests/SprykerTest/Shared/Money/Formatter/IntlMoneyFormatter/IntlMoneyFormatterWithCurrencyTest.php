<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Formatter\IntlMoneyFormatter;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Formatter
 * @group IntlMoneyFormatter
 * @group IntlMoneyFormatterWithCurrencyTest
 * Add your own group annotations below this line
 */
class IntlMoneyFormatterWithCurrencyTest extends AbstractIntlMoneyFormatterTest
{
    /**
     * @var string
     */
    public const AMOUNT = '1000';

    /**
     * @var string
     */
    public const CURRENCY = 'EUR';

    /**
     * @var string
     */
    public const LOCALE = 'de_DE';

    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $intlMoneyFormatter = new IntlMoneyFormatterWithCurrency($this->getTransferToMoneyConverterMock(), static::LOCALE);
        $this->assertInstanceOf(MoneyFormatterInterface::class, $intlMoneyFormatter);
    }

    /**
     * @return void
     */
    public function testFormatShouldReturnFormatted(): void
    {
        $intlMoneyFormatter = new IntlMoneyFormatterWithCurrency($this->getTransferToMoneyConverterMock(), static::LOCALE);
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount(static::AMOUNT);

        $isoCodeTransfer = new CurrencyTransfer();
        $isoCodeTransfer->setCode(static::CURRENCY);
        $moneyTransfer->setCurrency($isoCodeTransfer);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(static::LOCALE);
        $moneyTransfer->setLocale($localeTransfer);

        $formatted = $intlMoneyFormatter->format($moneyTransfer);
        $this->assertSame('10,00 €', $formatted);
    }
}
