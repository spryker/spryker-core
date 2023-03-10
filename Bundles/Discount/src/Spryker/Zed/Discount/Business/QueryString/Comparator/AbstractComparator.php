<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

abstract class AbstractComparator implements ComparatorInterface
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
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isValidValue($withValue): bool
    {
        if (!is_scalar($withValue)) {
            throw new ComparatorException(
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
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function accept(ClauseTransfer $clauseTransfer): bool
    {
        return strcasecmp($clauseTransfer->getOperatorOrFail(), $this->getExpression()) === 0;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isEmptyValue($value): bool
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
        $explodedValues = explode(ComparatorOperators::LIST_DELIMITER, $value);

        return array_map(function (string $explodedValue) {
            return strtolower(trim($explodedValue));
        }, $explodedValues);
    }
}
