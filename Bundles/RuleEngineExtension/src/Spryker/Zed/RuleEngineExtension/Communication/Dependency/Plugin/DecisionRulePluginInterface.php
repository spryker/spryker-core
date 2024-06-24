<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * Implement this interface to create a rule plugin that evaluates if provided transfer satisfies the clause.
 */
interface DecisionRulePluginInterface extends RulePluginInterface
{
    /**
     * Specification:
     * - Makes decision if given clause is satisfied by given satisfying transfer.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): bool;
}
