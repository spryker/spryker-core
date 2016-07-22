<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Builder;

use Generated\Shared\Transfer\MoneyTransfer;
use Money\Money;
use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Builder\MoneyBuilderInterface;
use Spryker\Shared\Money\Converter\MoneyToTransferConverterInterface;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Builder
 * @group MoneyBuilder
 */
class MoneyBuilderTest extends \PHPUnit_Framework_TestCase
{

    const DEFAULT_CURRENCY = 'EUR';
    const AMOUNT_INTEGER = 1000;
    const AMOUNT_FLOAT = 10.00;
    const AMOUNT_STRING = '1000';
    const OTHER_CURRENCY = 'USD';

    /**
     * @return void
     */
    public function testConstruct()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);
        $this->assertInstanceOf(MoneyBuilderInterface::class, $moneyBuilder);
    }

    /**
     * @return void
     */
    public function testGetMoneyWithIntegerAndWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_INTEGER);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @return void
     */
    public function testGetMoneyWithIntegerAndCurrencyShouldReturnMoneyTransfer()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_INTEGER, self::OTHER_CURRENCY);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @return void
     */
    public function testGetMoneyWithFloatAndWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_FLOAT);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @return void
     */
    public function testGetMoneyWithFloatAndCurrencyShouldReturnMoneyTransfer()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_FLOAT, self::OTHER_CURRENCY);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @return void
     */
    public function testGetMoneyWithStringAndWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_STRING);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @return void
     */
    public function testGetMoneyWithStringAndCurrencyShouldReturnMoneyTransfer()
    {
        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);

        $moneyTransfer = $moneyBuilder->getMoney(self::AMOUNT_STRING, self::OTHER_CURRENCY);
        $this->assertSame((string)self::AMOUNT_INTEGER, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency());
        $this->assertNotNull($moneyTransfer->getHash());
    }

    /**
     * @dataProvider forbiddenAmountTypes
     *
     * @return void
     */
    public function testGetMoneyShouldThrowExceptionWhenAmountTypeNotSupported($forbiddenAmountType)
    {
        $this->expectException(InvalidAmountArgumentException::class);

        $moneyBuilder = new MoneyBuilder($this->getMoneyToTransferConverterMock(), self::DEFAULT_CURRENCY);
        $moneyBuilder->getMoney($forbiddenAmountType);
    }

    /**
     * @return array
     */
    public function forbiddenAmountTypes()
    {
        return [
            ['10.00'],
            ['10,00'],
            ['1.000'],
            ['1,000'],
            [[]],
            [new \stdClass()],
            [true],
            [function (){}],
            [STDIN],
        ];
    }

    /**
     * @return \Spryker\Shared\Money\Converter\MoneyToTransferConverter
     */
    protected function getMoneyToTransferConverterMock()
    {
        $moneyToTransferConverterMock = $this->getMockBuilder(MoneyToTransferConverterInterface::class)->getMock();
        $moneyToTransferConverterMock->method('convert')->willReturnCallback([$this, 'convert']);

        return $moneyToTransferConverterMock;
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function convert(Money $money)
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount($money->getAmount())
            ->setCurrency($money->getCurrency()->getCode())
            ->setHash(spl_object_hash($money));

        return $moneyTransfer;
    }

}
