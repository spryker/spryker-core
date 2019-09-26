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
use Spryker\Shared\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 *
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
     * @param string[] $expectedValues
     *
     * @return void
     */
    public function testShipmentPriceDecisionRuleShouldMatchDifferentShipmentExpensePrices(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange
        $actualMatchedItemSkuList = [];

        // Act
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $actualMatchedItemSkuList[$itemTransfer->getSku()] = $this->tester->getFacade()->isPriceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        }

        // Assert
        $i = 0;
        foreach ($actualMatchedItemSkuList as $sku => $isSatisfied) {
            $this->assertEquals(
                $expectedValues[$sku],
                $isSatisfied,
                sprintf('The actual item shipment\'s expense price does not satisfied the rule (iteration #%d).', $i++)
            );
        }
    }

    /**
     * @return array
     */
    public function shipmentPriceDecisionRuleShouldMatchDifferentShipmentExpensePricesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 shipment, 1 shipment expense with gross unit price 1000, Clause: >= 100; expected: 1 price is matched' => $this->getDataWith1ShipmentExpenseWithQuoteLevelShipment(),
            'Item level shipment: 3 items, 2 shipments, 2 shipment expense with gross unit prices [1000, 50, 50], Clause: >= 100; expected: 1 price is matched' => $this->getDataWith1ShipmentExpenseWithItemLevelShipment(),
            'Item level shipment: 3 items, 3 shipments, 3 shipment expense with gross unit prices [50, 1000, 1000], Clause: >= 100; expected: 2 prices are matched' => $this->getDataWith2ShipmentExpensesWithItemLevelShipment(),
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
                ExpenseTransfer::UNIT_GROSS_PRICE => 1000, // Cents
                ExpenseTransfer::TYPE => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
            ])))
            ->build();

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [
            $quoteTransfer,
            $clauseTransfer,
            [$quoteTransfer->getItems()[0]->getSku() => true],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithItemLevelShipment(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 1])->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 2])->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1, 1000); // Cents
        $itemTransfer2 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 50);
        $itemTransfer3 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 50);

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [
            $quoteTransfer,
            $clauseTransfer, [
                $itemTransfer1->getSku() => true,
                $itemTransfer2->getSku() => false,
                $itemTransfer3->getSku() => false,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith2ShipmentExpensesWithItemLevelShipment(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 1])->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 2])->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1, 50); // Cents
        $itemTransfer2 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 1000);
        $itemTransfer3 = $this->addNewItemWithShipmentAndShipmentPriceIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2, 1000);

        $clauseTransfer = $this->createClauseTransferWithShipmentExpense();

        return [
            $quoteTransfer,
            $clauseTransfer,
            [
                $itemTransfer1->getSku() => false,
                $itemTransfer2->getSku() => true,
                $itemTransfer3->getSku() => true,
            ],
        ];
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
            ExpenseTransfer::TYPE => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
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
            ClauseTransfer::VALUE => 1, // Euro
            ClauseTransfer::OPERATOR => '>=',
            ClauseTransfer::ACCEPTED_TYPES => [ComparatorOperators::TYPE_NUMBER],
        ]))->build();
    }
}
