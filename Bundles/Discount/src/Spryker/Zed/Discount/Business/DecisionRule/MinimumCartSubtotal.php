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
     * @param QuoteTransfer $quoteTransfer
     * @param SpyDiscountDecisionRule $decisionRule
     *
     * @return ModelResult
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
