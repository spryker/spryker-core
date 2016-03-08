<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class MinimumCartSubtotal extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
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
