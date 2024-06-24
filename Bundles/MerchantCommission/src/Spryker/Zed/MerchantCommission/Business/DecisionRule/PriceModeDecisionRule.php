<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\DecisionRule;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;

class PriceModeDecisionRule implements DecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface
     */
    protected MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade)
    {
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): bool {
        return $this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $merchantCommissionCalculationRequestTransfer->getPriceMode());
    }
}
