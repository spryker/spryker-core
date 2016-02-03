<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;
use Spryker\Zed\Kernel\Business\ModelResult;

class MinimumCartSubtotal
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(QuoteTransfer $quoteTransfer, SpyDiscountDecisionRule $decisionRule)
    {
        $result = new ModelResult();

        if ($quoteTransfer->getTotals()->getSubtotal() >= $decisionRule->getValue()) {
            return $result;
        }

        return $result->setSuccess(false);
    }

}
