<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Formatter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterWithTypeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Formatter
 * @group MoneyFormatterTest
 * Add your own group annotations below this line
 */
class MoneyFormatterTest extends Unit
{
    public const AMOUNT = '1000';

    /**
     * @return void
     */
    public function testConstruct()
    {
        $moneyFormatter = new MoneyFormatter($this->getMoneyFormatterCollectionMock());
        $this->assertInstanceOf(MoneyFormatterWithTypeInterface::class, $moneyFormatter);
    }

    /**
     * @return void
     */
    public function testFormatShouldCallCollectionToGetFormatterAndReturnedFormatted()
    {
        $moneyFormatter = new MoneyFormatter($this->getMoneyFormatterCollectionMock());
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount(self::AMOUNT);

        $expectedFormatted = $this->format($moneyTransfer);
        $this->assertSame($expectedFormatted, $moneyFormatter->format($moneyTransfer, 'type'));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface
     */
    protected function getMoneyFormatterCollectionMock()
    {
        $moneyFormatterCollectionMock = $this->getMockBuilder(MoneyFormatterCollectionInterface::class)->getMock();
        $moneyFormatterCollectionMock->method('getFormatter')->willReturn($this->getMoneyFormatterMock());

        return $moneyFormatterCollectionMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    protected function getMoneyFormatterMock()
    {
        $moneyFormatterMock = $this->getMockBuilder(MoneyFormatterInterface::class)->getMock();
        $moneyFormatterMock->method('format')->willReturnCallback([$this, 'format']);

        return $moneyFormatterMock;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer)
    {
        return $moneyTransfer->getAmount() . ' Formatted';
    }
}
