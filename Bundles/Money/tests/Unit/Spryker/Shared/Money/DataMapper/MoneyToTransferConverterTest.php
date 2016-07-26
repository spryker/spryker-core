<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Converter
 * @group MoneyToTransferConverter
 */
class MoneyToTransferConverterTest extends \PHPUnit_Framework_TestCase
{

    const AMOUNT = 1000;
    const CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function testConvertShouldReturnTransfer()
    {
        $money = new Money(self::AMOUNT, new Currency(self::CURRENCY));
        $moneyToTransferConverter = new MoneyToTransferMapper();
        $moneyTransfer = $moneyToTransferConverter->convert($money);

        $this->assertInstanceOf(MoneyTransfer::class, $moneyTransfer);
        $this->assertSame((string)self::AMOUNT, $moneyTransfer->getAmount());

        $this->assertInstanceOf(CurrencyTransfer::class, $moneyTransfer->getCurrency());
        $this->assertSame(self::CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

}
