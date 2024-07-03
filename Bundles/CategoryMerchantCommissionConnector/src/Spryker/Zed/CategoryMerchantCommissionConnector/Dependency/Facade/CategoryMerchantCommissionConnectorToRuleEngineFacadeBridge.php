<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;

class CategoryMerchantCommissionConnectorToRuleEngineFacadeBridge implements CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface
{
    /**
     * @var \Spryker\Zed\RuleEngine\Business\RuleEngineFacadeInterface
     */
    protected $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\RuleEngine\Business\RuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct($ruleEngineFacade)
    {
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $comparedValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $comparedValue): bool
    {
        return $this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $comparedValue);
    }
}
