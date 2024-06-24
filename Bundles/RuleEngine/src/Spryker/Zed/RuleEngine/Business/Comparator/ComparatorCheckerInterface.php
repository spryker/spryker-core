<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface ComparatorCheckerInterface
{
    /**
     * @return list<string>
     */
    public function getCompoundComparatorExpressions(): array;

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isExistingComparator(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    public function isValidComparatorValue(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isLogicalComparator(string $token): bool;
}
