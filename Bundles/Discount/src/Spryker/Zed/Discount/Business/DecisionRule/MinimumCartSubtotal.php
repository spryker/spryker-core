<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;
use Spryker\Zed\Kernel\Business\ModelResult;

class MinimumCartSubtotal
{

    /**
     * @param CalculableInterface $order
     * @param SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $order, SpyDiscountDecisionRule $decisionRule)
    {
        $result = new ModelResult();

        $totalsTransfer = $order->getCalculableObject()->getTotals();
        if ($totalsTransfer && $totalsTransfer->getSubtotalWithoutItemExpenses() >= $decisionRule->getValue()) {
            return $result;
        }

        return $result->setSuccess(false);
    }

}
