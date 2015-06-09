<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRule;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class MinimumCartSubtotal // @TODO Missing interface
{
    const REASON_MINIMUM_CART_SUBTOTAL_NOT_REACHED = 'Minimum cart subtotal not reached';

    /**
     * @ param OrderInterface $order
     * @param CalculableInterface $order
     * @param DecisionRule $decisionRule
     *
     * @return $this|ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $order, DecisionRule $decisionRule)
    //public function isMinimumCartSubtotalReached(OrderInterface $order, DecisionRule $decisionRule)
    {
        $result = new ModelResult();

        if ($order->getCalculableObject()->getTotals()->getSubtotalWithoutItemExpenses() >= $decisionRule->getValue()) {
            return $result;
        }

        return $result->addError(self::REASON_MINIMUM_CART_SUBTOTAL_NOT_REACHED);
    }
}
