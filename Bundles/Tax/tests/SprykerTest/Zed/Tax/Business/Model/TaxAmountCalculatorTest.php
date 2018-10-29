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
use Spryker\Zed\Tax\Business\TaxFacadeInterface;

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
     * @dataProvider getSeparateTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     * @param int $expected
     *
     * @return void
     */
    public function testRecalculationOfSumTaxAmountForNonSplitMode(
        float $taxRate,
        int $price,
        int $quantity,
        int $expected
    ) {
        $taxFacade = $this->getTaxFacade();

        $nonSplitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price * $quantity, static::DEFAULT_QUANTITY);
        $calculableNonSplitObjectTransferMock = $this->createCalculableObjectTransferMock($nonSplitItemTransferCollection);

        $taxFacade->calculateTaxAmount($calculableNonSplitObjectTransferMock);
        $recalculatedNonSplitSumTaxAmount = $this->sumTaxAmount($calculableNonSplitObjectTransferMock);

        $this->assertEquals($expected, $recalculatedNonSplitSumTaxAmount);
    }

    /**
     * @dataProvider getSeparateTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     * @param int $expected
     *
     * @return void
     */
    public function testRecalculationOfSumTaxAmountForSplitMode(
        float $taxRate,
        int $price,
        int $quantity,
        int $expected
    ) {
        $taxFacade = $this->getTaxFacade();

        $splitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price, $quantity);
        $calculableSplitObjectTransferMock = $this->createCalculableObjectTransferMock($splitItemTransferCollection);

        $taxFacade->calculateTaxAmount($calculableSplitObjectTransferMock);
        $recalculatedSplitSumTaxAmount = $this->sumTaxAmount($calculableSplitObjectTransferMock);

        $this->assertEquals($expected, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @dataProvider getGroupTestData
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
        $taxFacade = $this->getTaxFacade();

        $nonSplitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price * $quantity, static::DEFAULT_QUANTITY);
        $splitItemTransferCollection = $this->createItemTransferCollection($taxRate, $price, $price, $quantity);

        $calculableNonSplitObjectTransferMock = $this->createCalculableObjectTransferMock($nonSplitItemTransferCollection);
        $calculableSplitObjectTransferMock = $this->createCalculableObjectTransferMock($splitItemTransferCollection);

        $taxFacade->calculateTaxAmount($calculableNonSplitObjectTransferMock);
        $taxFacade->calculateTaxAmount($calculableSplitObjectTransferMock);

        $recalculatedNonSplitSumTaxAmount = $this->sumTaxAmount($calculableNonSplitObjectTransferMock);
        $recalculatedSplitSumTaxAmount = $this->sumTaxAmount($calculableSplitObjectTransferMock);

        $this->assertEquals($recalculatedNonSplitSumTaxAmount, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @return array
     */
    public function getGroupTestData()
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
     * @return array
     */
    public function getSeparateTestData()
    {
        return [
            [7.25, 124, 20, 180],
            [10.11, 860, 5, 435],
            [35.22, 32, 10, 113],
            [28.45, 47, 13, 174],
            [14.26, 56, 8, 64],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function sumTaxAmount(CalculableObjectTransfer $calculableObjectTransfer): int
    {
        $items = $calculableObjectTransfer->getItems()->getArrayCopy();

        return array_reduce($items, function (?int $total, ItemTransfer $itemTransfer) {
            $total += $itemTransfer->getSumTaxAmount();
            return $total;
        });
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
     * @return \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected function getTaxFacade(): TaxFacadeInterface
    {
        return $this->tester->getLocator()->tax()->facade();
    }
}
