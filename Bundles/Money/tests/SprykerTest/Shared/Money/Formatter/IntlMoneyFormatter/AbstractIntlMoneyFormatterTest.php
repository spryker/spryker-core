<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Formatter\IntlMoneyFormatter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Formatter
 * @group IntlMoneyFormatter
 * @group AbstractIntlMoneyFormatterTest
 * Add your own group annotations below this line
 */
abstract class AbstractIntlMoneyFormatterTest extends Unit
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface
     */
    protected function getTransferToMoneyConverterMock()
    {
        $transferToMoneyConverterMock = $this->getMockBuilder(TransferToMoneyMapperInterface::class)->getMock();
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
        $money = new Money($moneyTransfer->getAmount(), new Currency($moneyTransfer->getCurrency()->getCode()));

        return $money;
    }
}
