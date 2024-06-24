<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\CollectorRule;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;

class ItemSkuCollectorRule implements CollectorRuleInterface
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
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array {
        $merchantCommissionCalculationRequestItemTransfers = [];
        foreach ($merchantCommissionCalculationRequestTransfer->getItems() as $merchantCommissionCalculationRequestItemTransfer) {
            if (!$this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $merchantCommissionCalculationRequestItemTransfer->getSkuOrFail())) {
                continue;
            }

            $merchantCommissionCalculationRequestItemTransfers[] = $merchantCommissionCalculationRequestItemTransfer;
        }

        return $merchantCommissionCalculationRequestItemTransfers;
    }
}
