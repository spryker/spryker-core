<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Kernel\Business\ModelResult;

class DecisionRuleEngine implements DecisionRuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function evaluate(
        DiscountTransfer $discountTransfer,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    ) {
        $errors = [];
        $result = new ModelResult();
        foreach ($decisionRulePlugins as $plugin) {
            $decisionRuleResult = $plugin->check($discountTransfer, $discountableContainer);
            $result->setSuccess($decisionRuleResult->isSuccess());
            $errors = array_merge($errors, $decisionRuleResult->getErrors());
        }

        $result->addErrors($errors);

        return $result;
    }

}
