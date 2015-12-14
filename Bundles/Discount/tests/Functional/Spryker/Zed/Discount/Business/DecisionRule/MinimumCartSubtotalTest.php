<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Discount\Business\DecisionRule;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use Spryker\Zed\Kernel\Locator;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;

/**
 * @group DiscountDecisionRuleMinimumCartSubtotalTest
 * @group Discount
 */
class MinimumCartSubtotalTest extends Test
{

    const MINIMUM_CART_SUBTOTAL_TEST_500 = 500;
    const CART_SUBTOTAL_400 = 400;
    const CART_SUBTOTAL_500 = 500;
    const CART_SUBTOTAL_1000 = 1000;

    /**
     * @return void
     */
    public function testShouldReturnTrueForAnOrderWithAHighEnoughSubtotal()
    {
        $quoteTransfer = $this->createQuoteTransferWithSubtotal(self::CART_SUBTOTAL_1000);
        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = $this->createMinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($quoteTransfer, $decisionRuleEntity);

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseForAnOrderWithATooLowSubtotal()
    {
        $quoteTransfer = $this->createQuoteTransferWithSubtotal(self::CART_SUBTOTAL_400);
        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = $this->createMinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($quoteTransfer, $decisionRuleEntity);

        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueForAnOrderWithAExactlyMatchingSubtotal()
    {
        $quoteTransfer = $this->createQuoteTransferWithSubtotal(self::CART_SUBTOTAL_500);

        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = $this->createMinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($quoteTransfer, $decisionRuleEntity);

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransferWithSubtotal($subtotal)
    {
        $quoteTransfer = new QuoteTransfer();
        $totals = new TotalsTransfer();
        $totals->setSubtotal($subtotal);
        $quoteTransfer->setTotals($totals);

        return $quoteTransfer;
    }

    /**
     * @param int $value
     *
     * @return SpyDiscountDecisionRule
     */
    protected function getDecisionRuleEntity($value)
    {
        $decisionRule = new SpyDiscountDecisionRule();
        $decisionRule->setValue($value);

        return $decisionRule;
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return MinimumCartSubtotal
     */
    protected function createMinimumCartSubtotal()
    {
        return new MinimumCartSubtotal();
    }

}
