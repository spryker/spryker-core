<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Tax\Business\Model\AccruedTaxCalculator;
use Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface;
use Spryker\Zed\Tax\Business\Model\Calculator\TaxAmountCalculator;
use Spryker\Zed\Tax\Business\Model\Exception\CalculationException;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group TaxAmountCalculatorTest
 * Add your own group annotations below this line
 */
class TaxAmountCalculatorTest extends Unit
{
    protected const PRICE_MODE_NET = 'NET_MODE';
    protected const DEFAULT_QUANTITY = 1;

    /**
     * @var \SprykerTest\Zed\Tax\TaxBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider getTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     *
     * @return void
     */
    public function testRecalculatedSumTaxAmountForNonSplitModeAndSplitShouldBeNotEqual(
        float $taxRate,
        int $price,
        int $quantity
    ) {
        $taxAmountCalculatorMock = $this->createMockedTaxAmountCalculatorNotFixed();

        $nonSplitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price * $quantity, static::DEFAULT_QUANTITY);

        $splitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price, $quantity);

        $this->assertCount(static::DEFAULT_QUANTITY, $nonSplitItemTransferCollection);
        $this->assertCount($quantity, $splitItemTransferCollection);

        $calculableNonSplitObjectTransferMock = $this->createCalculableObjectTransferMock($nonSplitItemTransferCollection);
        $calculableSplitObjectTransferMock = $this->createCalculableObjectTransferMock($splitItemTransferCollection);

        $taxAmountCalculatorMock->recalculate($calculableNonSplitObjectTransferMock);
        $taxAmountCalculatorMock->recalculate($calculableSplitObjectTransferMock);

        $recalculatedNonSplitSumTaxAmount = array_reduce(
            $calculableNonSplitObjectTransferMock->getItems()->getArrayCopy(),
            [$this, 'sumTaxAmountCalculatorCallback']
        );

        $recalculatedSplitSumTaxAmount = array_reduce(
            $calculableSplitObjectTransferMock->getItems()->getArrayCopy(),
            [$this, 'sumTaxAmountCalculatorCallback']
        );

        $this->assertNotEquals($recalculatedNonSplitSumTaxAmount, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @dataProvider getTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     *
     * @return void
     */
    public function testRecalculatedSumTaxAmountForNonSplitModeAndSplitShouldBeEqual(
        float $taxRate,
        int $price,
        int $quantity
    ) {
        $taxAmountCalculatorFixedMock = $this->createMockedTaxAmountCalculator();

        $nonSplitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price * $quantity, static::DEFAULT_QUANTITY);

        $splitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price, $quantity);

        $this->assertCount(static::DEFAULT_QUANTITY, $nonSplitItemTransferCollection);
        $this->assertCount($quantity, $splitItemTransferCollection);

        $calculableNonSplitObjectTransferMock = $this->createCalculableObjectTransferMock($nonSplitItemTransferCollection);
        $calculableSplitObjectTransferMock = $this->createCalculableObjectTransferMock($splitItemTransferCollection);

        $taxAmountCalculatorFixedMock->recalculate($calculableNonSplitObjectTransferMock);
        $taxAmountCalculatorFixedMock->recalculate($calculableSplitObjectTransferMock);

        $recalculatedNonSplitSumTaxAmount = array_reduce(
            $calculableNonSplitObjectTransferMock->getItems()->getArrayCopy(),
            [$this, 'sumTaxAmountCalculatorCallback']
        );

        $recalculatedSplitSumTaxAmount = array_reduce(
            $calculableSplitObjectTransferMock->getItems()->getArrayCopy(),
            [$this, 'sumTaxAmountCalculatorCallback']
        );

        $this->assertEquals($recalculatedNonSplitSumTaxAmount, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            [7.25, 3400, 20],
            [10.11, 100, 5],
            [20.22, 83, 10],
            [12.45, 137, 13],
            [14.26, 120, 8],
        ];
    }

    /**
     * @param int|null $total
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function sumTaxAmountCalculatorCallback(?int $total, ItemTransfer $itemTransfer): int
    {
        $total += $itemTransfer->getSumTaxAmount();
        return $total;
    }

    /**
     * @param float $taxRate
     * @param int $price
     * @param int $sumPrice
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(float $taxRate, int $price, int $sumPrice): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setTaxRate($taxRate)
            ->setUnitNetPrice($price)
            ->setSumNetPrice($sumPrice)
            ->setUnitPrice($price)
            ->setSumPrice($sumPrice)
            ->setOriginUnitNetPrice($price);

        return $itemTransfer;
    }

    /**
     * @param float $taxRate
     * @param int $price
     * @param int $sumPrice
     * @param int $quantity
     *
     * @return array
     */
    protected function createItemTransferCollection(float $taxRate, int $price, int $sumPrice, int $quantity = 1): array
    {
        $items = [];

        while ($quantity--) {
            $items[] = $this->createItemTransfer($taxRate, $price, $sumPrice);
        }

        return $items;
    }

    /**
     * @param array $itemTransferCollection
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function createCalculableObjectTransferMock(array $itemTransferCollection): CalculableObjectTransfer
    {
        $calculableObjectTransferMock = (new CalculableObjectTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setItems(new ArrayObject($itemTransferCollection));

        return $calculableObjectTransferMock;
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\Calculator\CalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockedTaxAmountCalculatorNotFixed()
    {
        $mockedPriceCalculationHelper = $this->createMockedPriceCalculationHelperNotFixed();

        $mockedAccruedTaxCalculator = $this->createMockedAccruedTaxCalculator($mockedPriceCalculationHelper);

        return $this->createMockedTaxAmountCalculator($mockedAccruedTaxCalculator);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface|null $mockedAccruedTaxCalculator
     *
     * @return \Spryker\Zed\Tax\Business\Model\Calculator\CalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockedTaxAmountCalculator(?AccruedTaxCalculatorInterface $mockedAccruedTaxCalculator = null)
    {
        if (!$mockedAccruedTaxCalculator) {
            $mockedAccruedTaxCalculator = $this->createMockedAccruedTaxCalculator();
        }

        $taxAmountCalculatorMock = $this->getMockBuilder(TaxAmountCalculator::class)
            ->setConstructorArgs([$mockedAccruedTaxCalculator])
            ->setMethods(null)
            ->getMock();

        return $taxAmountCalculatorMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface|null $mockedPriceCalculationHelper
     *
     * @return \Spryker\Zed\Tax\Business\Model\AccruedTaxCalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockedAccruedTaxCalculator(
        ?PriceCalculationHelperInterface $mockedPriceCalculationHelper = null
    ) {
        if (!$mockedPriceCalculationHelper) {
            $mockedPriceCalculationHelper = $this->createMockedPriceCalculationHelper();
        }

        $accruedTaxCalculatorMock = $this->getMockBuilder(AccruedTaxCalculator::class)
            ->setConstructorArgs([$mockedPriceCalculationHelper])
            ->setMethods(null)
            ->getMock();

        return $accruedTaxCalculatorMock;
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockedPriceCalculationHelper()
    {
        $accruedTaxCalculatorMock = $this->getMockBuilder(PriceCalculationHelper::class)
            ->setMethods(null)
            ->getMock();

        return $accruedTaxCalculatorMock;
    }

    /**
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\CalculationException
     *
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockedPriceCalculationHelperNotFixed()
    {
        $accruedTaxCalculatorMock = $this->getMockBuilder(PriceCalculationHelper::class)
            ->setMethods(['getTaxValueFromNetPrice'])
            ->getMock();

        $accruedTaxCalculatorMock->method('getTaxValueFromNetPrice')
            ->willReturnCallback(function ($netPrice, $taxPercentage) {
                $price = (int)$netPrice;

                if ($price < 0) {
                    throw new CalculationException('Invalid price value given.');
                }

                $amount = $netPrice * $taxPercentage / 100;

                return (int)round($amount);
            });

        return $accruedTaxCalculatorMock;
    }
}
