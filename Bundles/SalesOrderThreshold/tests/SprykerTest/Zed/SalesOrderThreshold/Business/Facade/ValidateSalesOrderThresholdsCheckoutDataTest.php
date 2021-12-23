<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Business\Facade;

use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\SalesOrderThresholdValueBuilder;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\Reader\SalesOrderThresholdReader;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface;
use SprykerTest\Zed\SalesOrderThreshold\Business\SalesOrderThresholdMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThreshold
 * @group Business
 * @group Facade
 * @group ValidateSalesOrderThresholdsCheckoutDataTest
 * Add your own group annotations below this line
 */
class ValidateSalesOrderThresholdsCheckoutDataTest extends SalesOrderThresholdMocks
{
    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM
     *
     * @var string
     */
    protected const THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM = 'hard-maximum-threshold';

    /**
     * @var \SprykerTest\Zed\SalesOrderThreshold\SalesOrderThresholdBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider validateSalesOrderThresholdsCheckoutDataProvider
     *
     * @param int $minimumThresholdValue
     * @param int $maximumThresholdValue
     * @param int $quoteItemQuantity
     * @param int $quoteItemPrice
     * @param int $expectedTotals
     * @param bool $isSuccess
     *
     * @return void
     */
    public function testValidateSalesOrderThresholdsCheckoutDataValidatesDifferentCombinations(
        int $minimumThresholdValue,
        int $maximumThresholdValue,
        int $quoteItemQuantity,
        int $quoteItemPrice,
        int $expectedTotals,
        bool $isSuccess
    ): void {
        // Arrange
        $this->cleanStaticProperty();
        $this->tester->setupThresholdDependencies($minimumThresholdValue, $maximumThresholdValue);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => $quoteItemQuantity,
            ItemTransfer::UNIT_NET_PRICE => $quoteItemPrice,
        ]))->build();

        $totalsTransfer = (new TotalsTransfer())->setSubtotal($expectedTotals);
        $quoteTransfer = $this->tester->createTestQuoteTransfer()->addItem($itemTransfer)->setTotals($totalsTransfer);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateSalesOrderThresholdsCheckoutData(
            (new CheckoutDataTransfer())->setQuote($quoteTransfer),
        );

        // Assert
        $this->assertSame($isSuccess, $checkoutResponseTransfer->getIsSuccess());
        $this->assertSame($isSuccess, $checkoutResponseTransfer->getErrors()->count() === 0);
    }

    /**
     * @return array<string, <array<int|bool>>
     */
    public function validateSalesOrderThresholdsCheckoutDataProvider(): array
    {
        return [
            'Not enough delta with one item quantity.' => [220, 500, 1, 160, 160, false],
            'Not enough delta with couple item quantity.' => [220, 500, 2, 80, 160, false],
            'Same minimum delta as totals when one item quantity.' => [220, 500, 1, 220, 220, true],
            'Same minimum delta as totals when couple item quantity.' => [220, 500, 2, 110, 220, true],
            'Enough delta when with one item quantity.' => [220, 500, 1, 221, 221, true],
            'Enough delta when with couple item quantity.' => [220, 500, 2, 150, 300, true],
            'Same maximum delta as totals when one item quantity.' => [220, 500, 1, 500, 500, true],
            'Same maximum delta as totals when couple item quantity.' => [220, 500, 2, 250, 500, true],
            'Too much delta when with one item quantity.' => [220, 500, 1, 501, 501, false],
            'Too much delta when with couple item quantity.' => [220, 500, 2, 300, 600, false],
        ];
    }

    /**
     * @return void
     */
    public function testValidateSalesOrderThresholdsCheckoutDataValidatesWithoutThresholds(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createTestQuoteTransfer();
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => 1,
            ItemTransfer::UNIT_NET_PRICE => 1234,
        ]))->build();

        $quoteTransfer->addItem($itemTransfer);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()
            ->validateSalesOrderThresholdsCheckoutData((new CheckoutDataTransfer())->setQuote($quoteTransfer));

        // Assert
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateSalesOrderThresholdsCheckoutDataExpectsQuoteToBeProvided(): void
    {
        // Arrange
        $checkoutDataTransfer = (new CheckoutDataTransfer())->setQuote(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->validateSalesOrderThresholdsCheckoutData($checkoutDataTransfer);
    }

    /**
     * @return void
     */
    public function testValidateSalesOrderThresholdsCheckoutDataExpectsCurrencyToBeProvided(): void
    {
        // Arrange
        $this->tester->setupThresholdDependencies(100, 500);

        $quoteTransfer = $this->tester->createTestQuoteTransfer();
        $quoteTransfer->setCurrency(null);

        $checkoutDataTransfer = (new CheckoutDataTransfer())->setQuote($quoteTransfer);
        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES, [
            $this->createSalesOrderThresholdDataSourceStrategyPluginMock(),
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->validateSalesOrderThresholdsCheckoutData($checkoutDataTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderThresholdDataSourceStrategyPluginMock(): SalesOrderThresholdDataSourceStrategyPluginInterface
    {
        $salesOrderThresholdDataSourceStrategyPluginMock = $this->getMockBuilder(SalesOrderThresholdDataSourceStrategyPluginInterface::class)
            ->onlyMethods(['findApplicableThresholds'])
            ->getMock();

        $salesOrderThresholdDataSourceStrategyPluginMock
            ->method('findApplicableThresholds')
            ->willReturn([(new SalesOrderThresholdValueBuilder([
                SalesOrderThresholdValueTransfer::THRESHOLD => static::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM,
            ]))->build()]);

        return $salesOrderThresholdDataSourceStrategyPluginMock;
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(SalesOrderThresholdReader::class);
        $property = $reflectedClass->getProperty('salesOrderThresholdTransfersCache');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
