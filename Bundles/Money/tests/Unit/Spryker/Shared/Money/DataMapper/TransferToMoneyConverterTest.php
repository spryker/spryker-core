<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\DataMapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Money;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group DataMapper
 * @group TransferToMoneyConverterTest
 */
class TransferToMoneyConverterTest extends PHPUnit_Framework_TestCase
{

    const AMOUNT = 1000;
    const CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function testConvertShouldReturnMoney()
    {
        $isoCodeTransfer = new CurrencyTransfer();
        $isoCodeTransfer->setCode(self::CURRENCY);

        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount(self::AMOUNT)
            ->setCurrency($isoCodeTransfer);

        $transferToMoneyConverter = new TransferToMoneyMapper();
        $money = $transferToMoneyConverter->convert($moneyTransfer);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertSame((string)self::AMOUNT, $money->getAmount());
        $this->assertSame(self::CURRENCY, $money->getCurrency()->getCode());
    }

}
