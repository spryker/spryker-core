<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDiscountConnector\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ClauseBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\CarrierDiscountDecisionRule;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithQuoteLevelShipment;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorBusinessFactory;
use Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group ShipmentDiscountCollector
 * @group CollectDiscountByShipmentMethodTest
 * Add your own group annotations below this line
 */
class CollectDiscountByShipmentMethodTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider collectDiscountByShipmentMethodShouldUseQuoteLevelShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param int[] $expectedValues
     *
     * @return void
     */
    public function testCollectDiscountByShipmentMethodShouldUseQuoteLevelShipmentExpenses(
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
                sprintf('The actual discountable item is not expected at the iteration #%d.', $i)
            );
        }
    }

    /**
     * @return array
     */
    public function collectDiscountByShipmentMethodShouldUseQuoteLevelShipmentExpensesDataProvider(): array
    {
        return [
            '1 quote shipment expense; expected: 1 discountable item' => $this->getDataWith1QuoteLevelShipmentExpense(),
            '2 quote shipment expenses; expected: 2 discountable items' => $this->getDataWith2QuoteLevelShipmentExpenses(),
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
        $quoteTransfer = (new QuoteBuilder())
            ->withExpense(
                (new ExpenseBuilder([
                    ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
                    ExpenseTransfer::UNIT_GROSS_PRICE => 100,
                ]))
            )
            ->withAnotherExpense(
                (new ExpenseBuilder([
                    ExpenseTransfer::TYPE => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
                    ExpenseTransfer::UNIT_GROSS_PRICE => 200,
                ]))
            )
            ->build();

        $clauseTransfer = (new ClauseBuilder())->build();

        return [$quoteTransfer, $clauseTransfer, [100, 200]];
    }

    /**
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorFacadeInterface
     */
    protected function getFacadeWithMockedDecisionRules(): ShipmentDiscountConnectorFacadeInterface
    {
        $mockedMultiShipmentDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRule::class)->disableOriginalConstructor()->getMock();
        $mockedMultiShipmentDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedQuoteLevelShipmentDecisionRule = $this->getMockBuilder(CarrierDiscountDecisionRuleWithQuoteLevelShipment::class)->disableOriginalConstructor()->getMock();
        $mockedQuoteLevelShipmentDecisionRule->method('isExpenseSatisfiedBy')->willReturn(true);

        $mockedBusinessFactory = $this->createPartialMock(
            ShipmentDiscountConnectorBusinessFactory::class,
            ['createCarrierDiscountDecisionRule', 'createCarrierDiscountDecisionRuleWithMultiShipment']
        );
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRule')->willReturn($mockedQuoteLevelShipmentDecisionRule);
        $mockedBusinessFactory->method('createCarrierDiscountDecisionRuleWithMultiShipment')->willReturn($mockedMultiShipmentDecisionRule);

        $facade = $this->tester->getFacade();
        $facade->setFactory($mockedBusinessFactory);

        return $facade;
    }
}
