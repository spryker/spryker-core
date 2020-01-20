<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Money;
use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Builder\MoneyBuilderInterface;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Builder
 * @group MoneyBuilderTest
 * Add your own group annotations below this line
 */
class MoneyBuilderTest extends Unit
{
    public const DEFAULT_CURRENCY = 'EUR';
    public const AMOUNT_INTEGER = 1000;
    public const AMOUNT_FLOAT = 10.00;
    public const AMOUNT_STRING = '1000';
    public const OTHER_CURRENCY = 'USD';

    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();
        $this->assertInstanceOf(MoneyBuilderInterface::class, $moneyBuilder);
    }

    /**
     * @return void
     */
    public function testFromIntegerWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromInteger(self::AMOUNT_INTEGER);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromIntegerWithCurrencyShouldReturnMoneyTransfer(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromInteger(self::AMOUNT_INTEGER, self::OTHER_CURRENCY);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromIntegerWithoutIntegerShouldThrowException(): void
    {
        $this->expectException(InvalidAmountArgumentException::class);

        $moneyBuilder = $this->getMoneyBuilder();
        $moneyBuilder->fromInteger(self::AMOUNT_STRING);
    }

    /**
     * @return void
     */
    public function testFromFloatWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromFloat(self::AMOUNT_FLOAT);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromFloatWithCurrencyShouldReturnMoneyTransfer(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromFloat(self::AMOUNT_FLOAT, self::OTHER_CURRENCY);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromFloatWithoutFloatShouldThrowException(): void
    {
        $this->expectException(InvalidAmountArgumentException::class);

        $moneyBuilder = $this->getMoneyBuilder();
        $moneyBuilder->fromFloat(self::AMOUNT_STRING);
    }

    /**
     * @return void
     */
    public function testFromStringWithoutCurrencyShouldReturnMoneyTransferWithDefaultCurrency(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromString(self::AMOUNT_STRING);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::DEFAULT_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromStringWithCurrencyShouldReturnMoneyTransfer(): void
    {
        $moneyBuilder = $this->getMoneyBuilder();

        $moneyTransfer = $moneyBuilder->fromString(self::AMOUNT_STRING, self::OTHER_CURRENCY);
        $this->assertSame(self::AMOUNT_STRING, $moneyTransfer->getAmount());
        $this->assertSame(self::OTHER_CURRENCY, $moneyTransfer->getCurrency()->getCode());
    }

    /**
     * @return void
     */
    public function testFromStringWithoutStringShouldThrowException(): void
    {
        $this->expectException(InvalidAmountArgumentException::class);

        $moneyBuilder = $this->getMoneyBuilder();
        $moneyBuilder->fromString(self::AMOUNT_INTEGER);
    }

    /**
     * @return void
     */
    public function testFromStringWithInvalidStringShouldThrowException(): void
    {
        $this->expectException(InvalidAmountArgumentException::class);

        $moneyBuilder = $this->getMoneyBuilder();
        $moneyBuilder->fromString(self::AMOUNT_STRING . '.00');
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMoneyToTransferConverterMock(): MoneyToTransferMapperInterface
    {
        $moneyToTransferConverterMock = $this->getMockBuilder(MoneyToTransferMapperInterface::class)->getMock();
        $moneyToTransferConverterMock->method('convert')->willReturnCallback([$this, 'convert']);

        return $moneyToTransferConverterMock;
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function convert(Money $money): MoneyTransfer
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount($money->getAmount());

        $isoCodeTransfer = new CurrencyTransfer();
        $isoCodeTransfer->setCode($money->getCurrency()->getCode());
        $moneyTransfer->setCurrency($isoCodeTransfer);

        return $moneyTransfer;
    }

    /**
     * @return \Spryker\Shared\Money\Builder\MoneyBuilderInterface
     */
    protected function getMoneyBuilder(): MoneyBuilderInterface
    {
        return new MoneyBuilder($this->getMoneyToTransferConverterMock(), new DecimalToIntegerConverter(), self::DEFAULT_CURRENCY);
    }
}
