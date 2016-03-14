<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\RuleConditionTransfer;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer);

}
