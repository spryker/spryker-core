<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\DataMapper;

use Generated\Shared\Transfer\MoneyTransfer;
use Money\Money;
use Spryker\Shared\Money\DataMapper\TransferToMoneyConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Converter
 * @group TransferToMoneyConverter
 */
class TransferToMoneyConverterTest extends \PHPUnit_Framework_TestCase
{

    const AMOUNT = 1000;
    const CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function testConvertShouldReturnMoney()
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount(self::AMOUNT)
            ->setCurrency('EUR');

        $transferToMoneyConverter = new TransferToMoneyConverter();
        $money = $transferToMoneyConverter->convert($moneyTransfer);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertSame((string)self::AMOUNT, $money->getAmount());
        $this->assertSame(self::CURRENCY, $money->getCurrency()->getCode());
    }

}
