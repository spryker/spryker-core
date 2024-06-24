<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator\Operator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;

interface CompareOperatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $withValue): bool;

    /**
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    public function isValidValue(mixed $withValue): bool;

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function accept(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool;

    /**
     * @return string
     */
    public function getExpression(): string;

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array;
}
