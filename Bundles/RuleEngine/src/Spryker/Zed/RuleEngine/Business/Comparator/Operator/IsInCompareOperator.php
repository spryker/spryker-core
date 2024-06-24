<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator\Operator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\RuleEngineConfig;

class IsInCompareOperator extends AbstractCompareOperator
{
    /**
     * @var string
     */
    protected const EXPRESSION = 'is in';

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

        $searchValues = $this->getExplodedListValue((string)$withValue);
        $clauseValues = $this->getExplodedListValue((string)$ruleEngineClauseTransfer->getValue());

        return array_intersect($searchValues, $clauseValues) !== [];
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            RuleEngineConfig::DATA_TYPE_LIST,
        ];
    }
}
