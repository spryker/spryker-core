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
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorBusinessFactory;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceBridge;
use Spryker\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group CollectDiscountByShipmentCarrierTest
 * Add your own group annotations below this line
 */
class CollectDiscountByShipmentCarrierTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider collectDiscountByShipmentCarrierShouldUseQuoteLevelShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentCarrierShouldUseQuoteLevelShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange

        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentCarrier($quoteTransfer, $clauseTransfer);

        // Assert
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
    public function collectDiscountByShipmentCarrierShouldUseQuoteLevelShipmentExpensesDataProvider(): array
    {
        return [
            'Quote level shipment: 1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1QuoteLevelShipmentExpense(),
            'Item level shipments: 2 quote shipment expenses; expected: 2 discountable items' => $this->getDataWith2QuoteLevelShipmentExpenses(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1QuoteLevelShipmentExpense(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withExpense(
                (new ExpenseBuilder([
                    ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
                    ExpenseTransfer::UNIT_GROSS_PRICE => 100,
                ]))
            )
            ->build();

        $clauseTransfer = (new ClauseBuilder())->build();

        return [$quoteTransfer, $clauseTransfer, [100]];
    }

    /**
     * @return array
     */
    protected function getDataWith2QuoteLevelShipmentExpenses(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 100);
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 200);

        $clauseTransfer = (new ClauseBuilder())->build();

        return [$quoteTransfer, $clauseTransfer, [100, 200]];
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
        $mockedMultiShipmentDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedMultiShipmentDecisionRule->method('isItemShipmentExpenseSatisfiedBy')->willReturn(true);

        $mockedQuoteLevelShipmentDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedQuoteLevelShipmentDecisionRule->method('isItemShipmentExpenseSatisfiedBy')->willReturn(true);

        $mockedBusinessFactory = $this->createPartialMock(
            ShipmentDiscountConnectorBusinessFactory::class,
            ['createCarrierDiscountDecisionRule', 'createCarrierDiscountDecisionRuleWithMultiShipment', 'getShipmentService']
        );
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentDecisionRule);
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentDecisionRule);
        $mockedBusinessFactory->method('getShipmentService')->willReturn(
            new ShipmentDiscountConnectorToShipmentServiceBridge($this->tester->getLocator()->shipment()->service())
        );

        $facade = $this->tester->getFacade();
        $facade->setFactory($mockedBusinessFactory);

        return $facade;
    }
}
