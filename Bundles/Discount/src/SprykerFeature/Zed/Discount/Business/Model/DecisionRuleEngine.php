<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class DecisionRuleEngine implements DecisionRuleInterface
{

    /**
     * @param DiscountTransfer $discountTransfer
     * @param CalculableInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return ModelResult
     */
    public function evaluate(
        DiscountTransfer $discountTransfer,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    ) {
        $errors = [];
        $result = new ModelResult();
        foreach ($decisionRulePlugins as $plugin) {
            $errors = array_merge($errors, $plugin->check($discountTransfer, $discountableContainer)->getErrors());
        }

        $result->addErrors($errors);

        return $result;
    }

}
