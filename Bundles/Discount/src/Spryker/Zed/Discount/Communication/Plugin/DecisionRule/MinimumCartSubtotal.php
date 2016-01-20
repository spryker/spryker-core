<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Business\ModelResult;

/**
 * @method DiscountFacade getFacade()
 */
class MinimumCartSubtotal extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountTransfer $discountTransfer
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ModelResult
     */
    public function check(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        $decisionRuleEntity = $this->getContext()[self::KEY_ENTITY];

        return $this->getFacade()->isMinimumCartSubtotalReached($quoteTransfer, $decisionRuleEntity);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getCurrencyManager()->convertDecimalToCent($value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->convertCentToDecimal($value);
    }

}
