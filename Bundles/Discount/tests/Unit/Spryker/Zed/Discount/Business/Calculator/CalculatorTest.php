<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Calculator\Calculator;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Calculator
 * @group CalculatorTest
 */
class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateShouldExecuteCollectorWhenThereIsDiscounts()
    {
        $discountTransfer = $this->createDiscountTransfer(100);
        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->once())
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->once())
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate([$discountTransfer], $quoteTransfer);

        $this->assertNotEmpty($collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCalculateShouldTakeHighestAmountExclusiveDiscountWhenThereIsMoreThanOne()
    {
        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false);
        $discounts[] = $this->createDiscountTransfer(30)->setIsExclusive(true);
        $discounts[] = $this->createDiscountTransfer(20)->setIsExclusive(true);

        $quoteTransfer = $this->createQuoteTransfer();

        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->exactly(3))
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->exactly(3))
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate($discounts, $quoteTransfer);

        $this->assertCount(1, $collectedDiscounts);
        $this->assertEquals(30, $collectedDiscounts[0]->getDiscount()->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWhenCalculatorNotFoundShouldThrowException()
    {
        $this->expectException(CalculatorException::class);

        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false)->setCalculatorPlugin('non existing');

        $quoteTransfer = $this->createQuoteTransfer();
        $discountableItems = $this->createDiscountableItemsFromQuoteTransfer($quoteTransfer);

        $specificationBuilderMock = $this->createSpecificationBuilderMock();
        $collectorSpecificationMock = $this->collectorSpecificationMock();
        $collectorSpecificationMock->expects($this->exactly(1))
            ->method('collect')
            ->willReturn($discountableItems);

        $specificationBuilderMock->expects($this->exactly(1))
            ->method('buildFromQueryString')
            ->willReturn($collectorSpecificationMock);

        $calculator = $this->createCalculator($specificationBuilderMock);

        $calculator->calculate($discounts, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculateWhenQueryBuilderThrowsExceptionShouldLogErrorAndReturnEmptyArray()
    {
        $discounts[] = $this->createDiscountTransfer(70)->setIsExclusive(false)->setCalculatorPlugin('non existing');

        $quoteTransfer = $this->createQuoteTransfer();
        $specificationBuilderMock = $this->createSpecificationBuilderMock();

        $specificationBuilderMock->expects($this->exactly(1))
            ->method('buildFromQueryString')
            ->willThrowException(new QueryStringException('test exception'));

        $calculator = $this->createCalculator($specificationBuilderMock);

        $collectedDiscounts = $calculator->calculate($discounts, $quoteTransfer);

        $this->assertEmpty($collectedDiscounts);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface|null $specificationBuilderMock
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface|null $messengerFacadeMock
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface|null $distributorMock
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface|null $calculatorPluginMock
     *
     * @return \Spryker\Zed\Discount\Business\Calculator\Calculator
     */
    protected function createCalculator(
        SpecificationBuilderInterface $specificationBuilderMock = null,
        DiscountToMessengerInterface $messengerFacadeMock = null,
        DistributorInterface $distributorMock = null,
        DiscountCalculatorPluginInterface $calculatorPluginMock = null
    ) {

        if (!$specificationBuilderMock) {
            $specificationBuilderMock = $this->createSpecificationBuilderMock();
        }

        if (!$messengerFacadeMock) {
            $messengerFacadeMock = $this->createMessengerFacadeBridgeMock();
        }

        if (!$distributorMock) {
            $distributorMock = $this->createDistributorMock();
        }

        if (!$calculatorPluginMock) {
            $calculatorPluginMock = $this->createCalculatorPluginMock();
            $calculatorPluginMock
                ->method('calculate')
                ->willReturnCallback(function ($discountableItems, $amount) {
                    return $amount;
                });
        }

        $calculatorPlugins = $this->createCalculatorPlugins($calculatorPluginMock);

        return new Calculator(
            $specificationBuilderMock,
            $messengerFacadeMock,
            $distributorMock,
            $calculatorPlugins
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected function createSpecificationBuilderMock()
    {
        return $this->getMockBuilder(SpecificationBuilderInterface::class)->getMock();
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculatorPluginMock
     *
     * @return array
     */
    protected function createCalculatorPlugins(DiscountCalculatorPluginInterface $calculatorPluginMock)
    {
        return [
            'test' => $calculatorPluginMock
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected function createMessengerFacadeBridgeMock()
    {
        return $this->getMockBuilder(DiscountToMessengerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function createCalculatorPluginMock()
    {
        return $this->getMockBuilder(DiscountCalculatorPluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Distributor\DistributorInterface
     */
    protected function createDistributorMock()
    {
        return $this->getMockBuilder(DistributorInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected function collectorSpecificationMock()
    {
        return $this->getMockBuilder(CollectorSpecificationInterface::class)->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|\Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function createDiscountableItemsFromQuoteTransfer(QuoteTransfer $quoteTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $discountableItemTransfer = new DiscountableItemTransfer();
            $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());
            $discountableItems[] = $discountableItemTransfer;
        }
        return $discountableItems;
    }

    /**
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function createDiscountTransfer($amount)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setCalculatorPlugin('test');
        $discountTransfer->setAmount($amount);

        return $discountTransfer;
    }

}
