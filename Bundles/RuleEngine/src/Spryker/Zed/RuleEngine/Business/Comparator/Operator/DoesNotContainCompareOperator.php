<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator\Operator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\RuleEngineConfig;

class DoesNotContainCompareOperator extends AbstractCompareOperator
{
    /**
     * @var string
     */
    protected const EXPRESSION = 'does not contain';

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $withValue): bool
    {
        if (!$this->isValidValue($withValue)) {
            return false;
        }

        return (stripos(trim($withValue), $ruleEngineClauseTransfer->getValueOrFail()) === false);
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            RuleEngineConfig::DATA_TYPE_STRING,
            RuleEngineConfig::DATA_TYPE_NUMBER,
        ];
    }
}
