<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator\Operator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException;
use Spryker\Zed\RuleEngine\RuleEngineConfig;

class LessCompareOperator extends AbstractCompareOperator
{
    /**
     * @var string
     */
    protected const EXPRESSION = '<';

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, $withValue): bool
    {
        if (!$this->isValidValue($withValue)) {
            return false;
        }

        return $withValue < $ruleEngineClauseTransfer->getValue();
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            RuleEngineConfig::DATA_TYPE_NUMBER,
        ];
    }

    /**
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    public function isValidValue(mixed $withValue): bool
    {
        if (!parent::isValidValue($withValue)) {
            return false;
        }

        if (preg_match(static::NUMBER_REGEXP, $withValue) === 0) {
            throw new CompareOperatorException('Only numeric value can be used together with "<" comparator.');
        }

        return true;
    }
}
