<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface ProductMerchantCommissionConnectorToRuleEngineFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $comparedValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $comparedValue): bool;
}
