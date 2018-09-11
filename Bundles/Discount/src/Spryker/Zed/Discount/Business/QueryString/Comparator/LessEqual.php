<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class LessEqual implements ComparatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $compareWithValue
     * @param string $withValue
     *
     * @return bool
     */
    public function compare(ClauseTransfer $compareWithValue, $withValue)
    {
        $this->isValidValue($withValue);

        return $withValue <= $compareWithValue->getValue();
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function accept(ClauseTransfer $clauseTransfer)
    {
        return (strcasecmp($clauseTransfer->getOperator(), $this->getExpression()) === 0);
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return '<=';
    }

    /**
     * @return string[]
     */
    public function getAcceptedTypes()
    {
        return [
            ComparatorOperators::TYPE_NUMBER,
        ];
    }

    /**
     * @param string $withValue
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isValidValue($withValue)
    {
        if (preg_match(ComparatorOperators::NUMBER_REGEXP, $withValue) === 0) {
            throw new ComparatorException('Only numeric value can be used together with "<=" comparator.');
        }

        return true;
    }
}
