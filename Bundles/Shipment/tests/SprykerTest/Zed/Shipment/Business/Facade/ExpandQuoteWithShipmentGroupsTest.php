<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group ExpandQuoteWithShipmentGroupsTest
 * Add your own group annotations below this line
 */
class ExpandQuoteWithShipmentGroupsTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @dataProvider getSkipRecalculationDataProvider
     *
     * @param int $expectedRecalculateQuoteCallCount
     * @param bool|null $skipRecalculation
     *
     * @return void
     */
    public function testSkipRecalculation(int $expectedRecalculateQuoteCallCount, ?bool $skipRecalculation = null): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentMethodDataHelper::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStoreOrFail()]);
        $shipmentData = [ShipmentTransfer::SHIPMENT_SELECTION => $shipmentMethodTransfer->getIdShipmentMethod()];

        $quoteTransfer = $this->tester->createQuoteTransfer($shipmentData)
            ->setStore($storeTransfer)
            ->setSkipRecalculation($skipRecalculation);

        $calculationFacadeMock = $this->createCalculationFacadeMock();
        $this->tester->setDependency(ShipmentDependencyProvider::FACADE_CALCULATION, $calculationFacadeMock);

        // Assert
        $calculationFacadeMock->expects($this->exactly($expectedRecalculateQuoteCallCount))
            ->method('recalculateQuote')
            ->willReturn($quoteTransfer);

        // Act
        $this->tester->getFacade()->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldSetSelectedShipmentMethod(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentMethodDataHelper::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStoreOrFail()]);
        $shipmentData = [ShipmentTransfer::SHIPMENT_SELECTION => $shipmentMethodTransfer->getIdShipmentMethod()];

        $quoteTransfer = $this->tester->createQuoteTransfer($shipmentData)->setStore($storeTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithShipmentGroups($quoteTransfer);

        // Assert
        $this->assertItemsHaveCorrectShipmentMethod($quoteTransfer, $shipmentMethodTransfer);
        $this->assertExpensesAreCorrect($quoteTransfer, $shipmentMethodTransfer);
    }

    /**
     * @return array<string, array<string, int|bool|null>>
     */
    protected function getSkipRecalculationDataProvider(): array
    {
        return [
            'Should skip recalculation when `skipRecalculation` is true.' => [
                'expectedRecalculateQuoteCallCount' => 0,
                'skipRecalculation' => true,
            ],
            'Should not skip recalculation when `skipRecalculation` is false.' => [
                'expectedRecalculateQuoteCallCount' => 1,
                'skipRecalculation' => false,
            ],
            'Should not skip recalculation when `skipRecalculation` is null.' => [
                'expectedRecalculateQuoteCallCount' => 1,
                'skipRecalculation' => null,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $expectedShipmentMethodTransfer
     *
     * @return void
     */
    protected function assertItemsHaveCorrectShipmentMethod(QuoteTransfer $quoteTransfer, ShipmentMethodTransfer $expectedShipmentMethodTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(
                $expectedShipmentMethodTransfer->getIdShipmentMethodOrFail(),
                $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getIdShipmentMethodOrFail(),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $expectedShipmentMethodTransfer
     *
     * @return void
     */
    protected function assertExpensesAreCorrect(QuoteTransfer $quoteTransfer, ShipmentMethodTransfer $expectedShipmentMethodTransfer): void
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $this->assertSame(static::SHIPMENT_EXPENSE_TYPE, $expenseTransfer->getTypeOrFail());
            $this->assertSame(1, $expenseTransfer->getQuantityOrFail());
            $this->assertSame(
                $expectedShipmentMethodTransfer->getIdShipmentMethodOrFail(),
                $expenseTransfer->getShipmentOrFail()->getMethodOrFail()->getIdShipmentMethodOrFail(),
            );
        }
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface
     */
    protected function createCalculationFacadeMock(): ShipmentToCalculationFacadeInterface
    {
        return $this->getMockBuilder(ShipmentToCalculationFacadeInterface::class)->getMock();
    }
}
