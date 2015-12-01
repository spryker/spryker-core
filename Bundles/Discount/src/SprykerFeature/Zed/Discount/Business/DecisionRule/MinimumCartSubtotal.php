<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\DecisionRule;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class MinimumCartSubtotal extends BaseDecisionRule
{

    /**
     * @param CalculableInterface $order
     * @param SpyDiscountDecisionRule $decisionRule
     *
     * @return ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $order, SpyDiscountDecisionRule $decisionRule)
    {
        $result = new ModelResult();

        if ($order->getCalculableObject()->getTotals()->getSubtotalWithoutItemExpenses() >= $decisionRule->getValue()) {
            return $result;
        }

        return $result->setSuccess(false);
    }

}
