<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Comparator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface;
use Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException;

class Comparator implements ComparatorInterface
{
    /**
     * @var string
     */
    protected const MATCH_ALL_IDENTIFIER = '*';

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
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $withValue): bool
    {
        if ($this->isMatchAllValue($ruleEngineClauseTransfer->getValueOrFail())) {
            return (string)$withValue !== '';
        }

        foreach ($this->operators as $operator) {
            if (!$operator->accept($ruleEngineClauseTransfer)) {
                continue;
            }

            $this->assertTypeAccepted($ruleEngineClauseTransfer->getAcceptedTypes(), $operator);

            return $operator->compare($ruleEngineClauseTransfer, $withValue);
        }

        throw new CompareOperatorException(
            sprintf('Comparison operator "%s" not found.', $ruleEngineClauseTransfer->getOperatorOrFail()),
        );
    }

    /**
     * @param list<string> $withTypes
     * @param \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface $operator
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\CompareOperatorException
     *
     * @return bool
     */
    protected function assertTypeAccepted(array $withTypes, CompareOperatorInterface $operator): bool
    {
        if ($this->isTypeSet($withTypes, $operator) === true) {
            return true;
        }

        throw new CompareOperatorException(
            sprintf(
                '"%s" operator does not accept any of "%s" types',
                get_class($operator),
                implode(',', $withTypes),
            ),
        );
    }

    /**
     * @param list<string> $withTypes
     * @param \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface $comparator
     *
     * @return bool
     */
    protected function isTypeSet(array $withTypes, CompareOperatorInterface $comparator): bool
    {
        foreach ($withTypes as $withType) {
            if (in_array($withType, $comparator->getAcceptedTypes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $withValue
     *
     * @return bool
     */
    protected function isMatchAllValue(string $withValue): bool
    {
        return $withValue === static::MATCH_ALL_IDENTIFIER;
    }
}
