<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDiscountConnector\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ClauseBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group ShipmentPriceDecisionRuleTest
 * Add your own group annotations below this line
 */
class ShipmentPriceDecisionRuleTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentPriceDecisionRuleShouldMatchDifferentShipmentExpensePricesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string[] $expectedMatchedItemSkuList
     *
     * @return void
     */
    public function testShipmentPriceDecisionRuleShouldMatchDifferentShipmentExpensePrices(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedMatchedItemSkuList
    ): void {
        // Arrange
        $actualMatchedItemSkuList = [];

        // Act
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $isRuleMatched = $this->tester->getFacade()->isPriceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
            if (!$isRuleMatched) {
                continue;
            }

            $actualMatchedItemSkuList[] = $itemTransfer->getSku();
        }

        // Assert
        $this->assertCount(count($expectedMatchedItemSkuList), $actualMatchedItemSkuList, 'Actual and expected rule matches counts are not the same.');

        foreach ($actualMatchedItemSkuList as $i => $sku) {
            $this->assertContains($sku, $expectedMatchedItemSkuList, sprintf('Actual and expected rule decisions do not match (iteration #%d).', $i));
        }
    }

    /**
     * @return array
     */
    public function shipmentPriceDecisionRuleShouldMatchDifferentShipmentExpensePricesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 shipment, 1 shipment expense with gross unit price 1000, Clause: >= 100; expected: 1 price matches' => $this->getDataWith1ShipmentExpenseWithQuoteLevelShipment(),
            'Item level shipment: 3 items, 2 shipments, 2 shipment expense with gross unit prices [1000, 50, 50], Clause: >= 100; expected: 1 price matches' => $this->getDataWith1ShipmentExpenseWithItemLevelShipment(),
            'Item level shipment: 3 items, 3 shipments, 3 shipment expense with gross unit prices [50, 1000, 1000], Clause: >= 100; expected: 2 prices match' => $this->getDataWith2ShipmentExpensesWithItemLevelShipment(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithQuoteLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment()
            ->withItem()
            ->withExpense((new ExpenseBuilder([
                ExpenseTransfer::UNIT_GROSS_PRICE => 100000,
                ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            ])))
            ->build();

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [$quoteTransfer, $clauseTransfer, [$quoteTransfer->getItems()[0]->getSku()]];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithItemLevelShipment(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod()->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod()->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1, 100000);
        $itemTransfer2 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 5000);
        $itemTransfer3 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 5000);

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [$quoteTransfer, $clauseTransfer, [$itemTransfer1->getSku()]];
    }

    /**
     * @return array
     */
    protected function getDataWith2ShipmentExpensesWithItemLevelShipment(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod()->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod()->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1, 5000);
        $itemTransfer2 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 100000);
        $itemTransfer3 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 100000);

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [$quoteTransfer, $clauseTransfer, [$itemTransfer2->getSku(), $itemTransfer3->getSku()]];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $unitGrossPrice
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        int $unitGrossPrice
    ): ItemTransfer {
        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::UNIT_GROSS_PRICE => $unitGrossPrice,
            ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransferWithShipmentExpense(): ClauseTransfer
    {
        return (new ClauseBuilder([
            ClauseTransfer::FIELD => 'getUnitGrossPrice',
            ClauseTransfer::VALUE => 100,
            ClauseTransfer::OPERATOR => '>=',
            ClauseTransfer::ACCEPTED_TYPES => [ComparatorOperators::TYPE_NUMBER],
        ]))->build();
    }
}
