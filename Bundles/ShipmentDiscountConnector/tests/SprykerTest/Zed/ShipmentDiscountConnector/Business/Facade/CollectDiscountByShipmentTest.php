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
use Spryker\Shared\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\MethodDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentPriceDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\MethodDiscountDecisionRule as MethodDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\ShipmentPriceDiscountDecisionRule as ShipmentPriceDiscountDecisionRuleWithQuoteLevelShipment;
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
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentCarrierShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentCarrier($quoteTransfer, new ClauseTransfer());

        // Assert
        foreach ($discountableItems as $i => $discountableItemTransfer) {
            $this->assertEquals(
                $expectedValues[$i],
                $discountableItemTransfer->getUnitPrice(),
                sprintf('The actual discountable item expense\'s value is invalid (iteration #%d).', $i)
            );
        }
    }

    /**
     * @dataProvider collectDiscountByShipmentMethodShouldUseQuoteShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentMethodShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentMethod($quoteTransfer, new ClauseTransfer());

        // Assert
        foreach ($discountableItems as $i => $discountableItemTransfer) {
            $this->assertEquals(
                $expectedValues[$i],
                $discountableItemTransfer->getUnitPrice(),
                sprintf('The actual discountable item expense\'s value is invalid (iteration #%d).', $i)
            );
        }
    }

    /**
     * @dataProvider collectDiscountByShipmentPriceShouldUseQuoteShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentPriceShouldUseQuoteShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Act
        $discountableItems = $this->getFacadeWithMockedDecisionRules()->collectDiscountByShipmentPrice($quoteTransfer, new ClauseTransfer());

        // Assert
        foreach ($discountableItems as $i => $discountableItemTransfer) {
            $this->assertEquals(
                $expectedValues[$i],
                $discountableItemTransfer->getUnitPrice(),
                sprintf('The actual discountable item expense\'s value is invalid (iteration #%d).', $i)
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
                    ExpenseTransfer::TYPE => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
                    ExpenseTransfer::UNIT_GROSS_PRICE => 100,
                ]))
            )
            ->build();

        return [$quoteTransfer, [100]];
    }

    /**
     * @return array
     */
    protected function getDataWith1ShipmentExpenseWithItemLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 100);

        return [$quoteTransfer, [100]];
    }

    /**
     * @return array
     */
    protected function getDataWith2ShipmentExpensesWithItemLevelShipment(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 100);
        $quoteTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 200);

        return [$quoteTransfer, [100, 200]];
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
            ExpenseTransfer::TYPE => ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE,
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

        $mockedMultiShipmentPriceDiscountDecisionRule = $this->getMockBuilder(ShipmentPriceDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentPriceDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);
        $mockedQuoteLevelShipmentPriceDiscountDecisionRule = $this->getMockBuilder(ShipmentPriceDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentPriceDiscountDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedBusinessFactory = $this->getMockBuilder(ShipmentDiscountConnectorBusinessFactory::class)->setMethods([
            'createCarrierDiscountDecisionRule',
            'createCarrierDiscountDecisionRuleWithMultiShipment',
            'createMethodDiscountDecisionRule',
            'createMethodDiscountDecisionRuleWithMultiShipment',
            'createShipmentPriceDiscountDecisionRule',
            'createShipmentPriceDiscountDecisionRuleWithMultiShipment',
            'getShipmentService',
        ])->getMock();
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentCarrierDiscountDecisionRule);
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentCarrierDiscountDecisionRule);

        $mockedBusinessFactory->method('createMethodDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentMethodDiscountDecisionRule);
        $mockedBusinessFactory->method('createMethodDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentMethodDiscountDecisionRule);

        $mockedBusinessFactory->method('createShipmentPriceDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentPriceDiscountDecisionRule);
        $mockedBusinessFactory->method('createShipmentPriceDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentPriceDiscountDecisionRule);

        $shipmentDiscountConnectorToShipmentServiceBridge = $this->createShipmentDiscountConnectorToShipmentServiceBridge();
        $mockedBusinessFactory->method('getShipmentService')->willReturn($shipmentDiscountConnectorToShipmentServiceBridge);

        $facade = $this->tester->getFacade();
        $facade->setFactory($mockedBusinessFactory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceBridge
     */
    protected function createShipmentDiscountConnectorToShipmentServiceBridge(): ShipmentDiscountConnectorToShipmentServiceBridge
    {
        return new ShipmentDiscountConnectorToShipmentServiceBridge($this->tester->getLocator()->shipment()->service());
    }
}
