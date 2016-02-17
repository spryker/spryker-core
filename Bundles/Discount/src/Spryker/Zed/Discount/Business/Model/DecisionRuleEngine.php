<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\ModelResult;

class DecisionRuleEngine implements DecisionRuleInterface
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
    ) {
        $errors = [];
        $result = new ModelResult();
        foreach ($decisionRulePlugins as $plugin) {
            $decisionRuleResult = $plugin->check($discountTransfer, $quoteTransfer);
            $result->setSuccess($decisionRuleResult->isSuccess());
            $errors = array_merge($errors, $decisionRuleResult->getErrors());
        }

        $result->addErrors($errors);

        return $result;
    }

}
