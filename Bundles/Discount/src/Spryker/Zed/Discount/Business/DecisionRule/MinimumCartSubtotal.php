<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Kernel\Business\ModelResult;

class MinimumCartSubtotal
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $order
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
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
