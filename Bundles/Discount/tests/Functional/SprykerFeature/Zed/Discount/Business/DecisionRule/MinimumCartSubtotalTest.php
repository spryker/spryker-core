<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Discount\Business\DecisionRule;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Class MinimumCartSubtotalTest
 * @group DiscountDecisionRuleMinimumCartSubtotalTest
 * @group Discount
 * @package Unit\SprykerFeature\Zed\Discount\Business\DecisionRule
 */
class MinimumCartSubtotalTest extends Test
{
    const MINIMUM_CART_SUBTOTAL_TEST_500 = 500;
    const CART_SUBTOTAL_400 = 400;
    const CART_SUBTOTAL_500 = 500;
    const CART_SUBTOTAL_1000 = 1000;

    public function testShouldReturnTrueForAnOrderWithAHighEnoughSubtotal()
    {
        $locator = $this->getLocator();
        $order = new OrderTransfer();
        $totals = new TotalsTransfer();
        $totals->setSubtotalWithoutItemExpenses(self::CART_SUBTOTAL_1000);
        $order->setTotals($totals);

        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = new MinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($order, $decisionRuleEntity);

        $this->assertTrue($result->isSuccess());
    }

    public function testShouldReturnFalseForAnOrderWithATooLowSubtotal()
    {
        $locator = $this->getLocator();
        /* @var Order $order */
        $order = new \Generated\Shared\Transfer\OrderTransfer();

        /* @var TotalsInterface $totals */
        $totals = new \Generated\Shared\Transfer\TotalsTransfer();
        $totals->setSubtotalWithoutItemExpenses(self::CART_SUBTOTAL_400);
        $order->setTotals($totals);

        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = new MinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($order, $decisionRuleEntity);

        $this->assertFalse($result->isSuccess());
    }

    public function testShouldReturnTrueForAnOrderWithAExactlyMatchingSubtotal()
    {
        $locator = $this->getLocator();
        /* @var Order $order */
        $order = new \Generated\Shared\Transfer\OrderTransfer();

        /* @var TotalsInterface $totals */
        $totals = new \Generated\Shared\Transfer\TotalsTransfer();
        $totals->setSubtotalWithoutItemExpenses(self::CART_SUBTOTAL_500);
        $order->setTotals($totals);

        $decisionRuleEntity = $this->getDecisionRuleEntity(self::MINIMUM_CART_SUBTOTAL_TEST_500);

        $decisionRule = new MinimumCartSubtotal();
        $result = $decisionRule->isMinimumCartSubtotalReached($order, $decisionRuleEntity);

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @param int $value
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule
     */
    protected function getDecisionRuleEntity($value)
    {
        $decisionRule = new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule();
        $decisionRule->setValue($value);

        return $decisionRule;
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return  Locator::getInstance();
    }
}
