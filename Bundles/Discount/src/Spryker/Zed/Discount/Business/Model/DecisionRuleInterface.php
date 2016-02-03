<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\ModelResult;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

interface DecisionRuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function evaluate(
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer,
        array $decisionRulePlugins
    );

}
