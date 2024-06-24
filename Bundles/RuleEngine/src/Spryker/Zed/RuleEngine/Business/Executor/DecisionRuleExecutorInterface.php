<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Executor;

use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface DecisionRuleExecutorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): bool;
}
