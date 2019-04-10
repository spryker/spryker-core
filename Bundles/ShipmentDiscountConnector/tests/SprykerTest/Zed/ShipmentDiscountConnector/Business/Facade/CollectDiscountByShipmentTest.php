<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDiscountConnector\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\MethodDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\MethodDiscountDecisionRule as MethodDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorBusinessFactory;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group CollectDiscountByShipmentTest
 * Add your own group annotations below this line
 */
class CollectDiscountByShipmentTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider collectDiscountByShipmentCarrierShouldUseQuoteShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentCarrierShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange

        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentCarrier($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertDiscountableItemsHasExpectedPriceValues($discountableItems, $expectedValues);
    }

    /**
     * @dataProvider collectDiscountByShipmentMethodShouldUseQuoteShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentMethodShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange

        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentMethod($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertDiscountableItemsHasExpectedPriceValues($discountableItems, $expectedValues);
    }

    /**
     * @dataProvider collectDiscountByShipmentPriceShouldUseQuoteShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentPriceShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange

        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentPrice($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertDiscountableItemsHasExpectedPriceValues($discountableItems, $expectedValues);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param array $expectedValues
     *
     * @return void
     */
    protected function assertDiscountableItemsHasExpectedPriceValues(
        array $discountableItems,
        array $expectedValues
    ): void {
        $this->assertCount(
            count($expectedValues),
            $discountableItems,
            'Actual and expected discountable items count does not match.'
        );

        foreach ($discountableItems as $i => $discountableItemTransfer) {
            $this->assertContains(
                $discountableItemTransfer->getUnitPrice(),
                $expectedValues,
                sprintf('The actual discountable item is not expected (iteration #%d).', $i)
            );
        }
    }

    /**
     * @return array
     */
    public function collectDiscountByShipmentCarrierShouldUseQuoteShipmentExpensesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithQuoteLevelShipment(),
            'Item level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithItemLevelShipment(),
            'Item level shipments: 2 quote shipment expenses; expected: 2 discountable items' => $this->getDataWith2ShipmentExpensesWithItemLevelShipment(),
        ];
    }

    /**
     * @return array
     */
    public function collectDiscountByShipmentMethodShouldUseQuoteShipmentExpensesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithQuoteLevelShipment(),
            'Item level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithItemLevelShipment(),
            'Item level shipments: 2 quote shipment expenses; expected: 2 discountable items' => $this->getDataWith2ShipmentExpensesWithItemLevelShipment(),
        ];
    }

    /**
     * @return array
     */
    public function collectDiscountByShipmentPriceShouldUseQuoteShipmentExpensesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithQuoteLevelShipment(),
            'Item level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1ShipmentExpenseWithItemLevelShipment(),
            'Item level shipments: 2 quote shipment expenses; expected: 2 discountable items' => $this->getDataWith2ShipmentExpensesWithItemLevelShipment(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithQuoteLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withExpense(
                (new ExpenseBuilder([
                    ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
                    ExpenseTransfer::UNIT_GROSS_PRICE => 100,
                ]))
            )
            ->build();

        return [$quoteTransfer, new ClauseTransfer(), [100]];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithItemLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 100);

        return [$quoteTransfer, new ClauseTransfer(), [100]];
    }

    /**
     * @return array
     */
    protected function getDataWith2ShipmentExpensesWithItemLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 100);
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 200);

        return [$quoteTransfer, new ClauseTransfer(), [100, 200]];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $price
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addNewItemAndExpenseIntoQuoteTransfer(QuoteTransfer $quoteTransfer, int $price): QuoteTransfer
    {
        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress()
            ->withMethod()
            ->build();

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::UNIT_GROSS_PRICE => $price,
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface
     */
    protected function getFacadeWithMockedDecisionRules(): ShipmentDiscountConnectorFacadeInterface
    {
        $mockedMultiShipmentCarrierDiscountDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentCarrierDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedQuoteLevelShipmentCarrierDiscountDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentCarrierDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedMultiShipmentMethodDiscountDecisionRule = $this->getMockBuilder(MethodDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentMethodDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedQuoteLevelShipmentMethodDiscountDecisionRule = $this->getMockBuilder(MethodDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentMethodDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedMultiShipmentPriceDiscountDecisionRule = $this->getMockBuilder(MethodDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentPriceDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedQuoteLevelShipmentPriceDiscountDecisionRule = $this->getMockBuilder(MethodDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentPriceDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedBusinessFactory = $this->createPartialMock(
            ShipmentDiscountConnectorBusinessFactory::class,
            [
                'createCarrierDiscountDecisionRule',
                'createCarrierDiscountDecisionRuleWithMultiShipment',
                'createMethodDiscountDecisionRule',
                'createMethodDiscountDecisionRuleWithMultiShipment',
                'createShipmentPriceDiscountDecisionRule',
                'createShipmentPriceDiscountDecisionRuleWithMultiShipment',
                'getShipmentService',
            ]
        );
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentCarrierDiscountDecisionRule);
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentCarrierDiscountDecisionRule);

        $mockedBusinessFactory->method('createMethodDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentMethodDiscountDecisionRule);
        $mockedBusinessFactory->method('createMethodDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentMethodDiscountDecisionRule);

        $mockedBusinessFactory->method('createShipmentPriceDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentPriceDiscountDecisionRule);
        $mockedBusinessFactory->method('createShipmentPriceDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentPriceDiscountDecisionRule);
        $mockedBusinessFactory->method('getShipmentService')->willReturn(
            new ShipmentDiscountConnectorToShipmentServiceBridge($this->tester->getLocator()->shipment()->service())
        );

        $facade = $this->tester->getFacade();
        $facade->setFactory($mockedBusinessFactory);

        return $facade;
    }
}
