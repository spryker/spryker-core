<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;

class ComparatorChecker implements ComparatorCheckerInterface
{
    /**
     * @var string
     */
    public const LOGICAL_COMPARATOR_AND = 'and';

    /**
     * @var string
     */
    public const LOGICAL_COMPARATOR_OR = 'or';

    /**
     * @var list<string>
     */
    protected const LOGICAL_COMPARATORS = [
        self::LOGICAL_COMPARATOR_AND,
        self::LOGICAL_COMPARATOR_OR,
    ];

    /**
     * @var list<\Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface>
     */
    protected array $operators;

    /**
     * @param list<\Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface> $operators
     */
    public function __construct(array $operators)
    {
        $this->operators = $operators;
    }

    /**
     * @return list<string>
     */
    public function getCompoundComparatorExpressions(): array
    {
        $combinedOperators = [];
        foreach ($this->operators as $comparator) {
            $expression = $comparator->getExpression();
            $parts = explode(' ', trim($expression));
            if (count($parts) <= 1) {
                continue;
            }

            $combinedOperators[] = $parts;
        }

        return array_unique(array_merge(...$combinedOperators));
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isExistingComparator(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
    {
        foreach ($this->operators as $operator) {
            if ($operator->accept($ruleEngineClauseTransfer) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return bool
     */
    public function isValidComparatorValue(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
    {
        foreach ($this->operators as $operator) {
            if (!$operator->accept($ruleEngineClauseTransfer)) {
                continue;
            }

            $operator->isValidValue($ruleEngineClauseTransfer->getValue());
        }

        return true;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isLogicalComparator(string $token): bool
    {
        return in_array($token, static::LOGICAL_COMPARATORS, true);
    }
}
