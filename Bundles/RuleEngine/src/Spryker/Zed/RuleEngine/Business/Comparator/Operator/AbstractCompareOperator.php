<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator\Operator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException;
use Spryker\Zed\RuleEngine\RuleEngineConfig;

abstract class AbstractCompareOperator implements CompareOperatorInterface
{
    /**
     * @var string
     */
    protected const EXPRESSION = '';

    /**
     * @var bool
     */
    protected const ALLOW_EMPTY_VALUE = false;

    /**
     * @var string
     */
    protected const NUMBER_REGEXP = '/[0-9\.\,]+/';

    /**
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    public function isValidValue(mixed $withValue): bool
    {
        if (!is_scalar($withValue)) {
            throw new CompareOperatorException(
                sprintf('Only scalar value can be used together with "%s" comparator.', $this->getExpression()),
            );
        }

        return static::ALLOW_EMPTY_VALUE || !$this->isEmptyValue($withValue);
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return static::EXPRESSION;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function accept(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
    {
        return strcasecmp($ruleEngineClauseTransfer->getOperatorOrFail(), $this->getExpression()) === 0;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isEmptyValue(mixed $value): bool
    {
        return (string)$value === '';
    }

    /**
     * @param string $value
     *
     * @return list<string>
     */
    protected function getExplodedListValue(string $value): array
    {
        $explodedValues = explode(RuleEngineConfig::LIST_DELIMITER, $value);

        return array_map(function (string $explodedValue) {
            return strtolower(trim($explodedValue));
        }, $explodedValues);
    }
}
