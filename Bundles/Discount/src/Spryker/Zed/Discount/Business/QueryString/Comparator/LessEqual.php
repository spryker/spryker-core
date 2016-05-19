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
     * @param ClauseTransfer $compareWithValue
     * @param string $withValue
     *
     * @return bool
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     */
    public function compare(ClauseTransfer $compareWithValue, $withValue)
    {
        if (!is_numeric($withValue)) {
            throw new ComparatorException('Only numeric value can be used together with "<=" comparator.');
        }

        return $compareWithValue->getValue() <= $withValue;
    }

    /**
     * @param ClauseTransfer $clauseTransfer
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
            ComparatorOperators::TYPE_INTEGER,
        ];
    }
}
