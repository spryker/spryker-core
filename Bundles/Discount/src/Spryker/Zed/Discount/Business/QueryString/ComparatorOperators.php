<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface;

class ComparatorOperators
{
    const TYPE_INTEGER  = 'integer';
    const TYPE_STRING  = 'string';
    const TYPE_LIST = 'list';

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface[]
     */
    protected $operators = [];

    /**
     * @param ComparatorInterface[] $operators
     */
    public function __construct(array $operators)
    {
        $this->operators = $operators;
    }

    /**
     * @param ClauseTransfer $clauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     * @throws ComparatorException
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue)
    {
        if (!$withValue) {
            return false;
        }

        if ($this->isMatchAllValue($clauseTransfer->getValue())) {
            return true;
        }

        foreach ($this->operators as $operator) {
            if (!$operator->accept($clauseTransfer)) {
                 continue;
            }

            $withTypes = $clauseTransfer->getAcceptedTypes();
            if ($this->isTypeAccepted($withTypes, $operator->getAcceptedTypes()) === false) {
                throw new ComparatorException(
                   sprintf(
                       '""%s" operator does not accept any of "%s" types',
                       get_class($operator),
                       implode(',', $withTypes)
                   )
                );
            }


            return $operator->compare($clauseTransfer, $withValue);
        }

        throw new ComparatorException(
            sprintf(
                'Comparison operator "%s" not found.',
                $clauseTransfer->getOperator()
            )
        );
    }

    /**
     * @param array|string[] $withTypes
     * @param array|string[] $operatorTypes
     *
     * @return bool
     */
    protected function isTypeAccepted(array $withTypes, array $operatorTypes)
    {
        foreach ($withTypes as $withType) {
            if (in_array($withType, $operatorTypes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array|string[] $acceptedTypes
     *
     * @return array|string[]
     */
    public function getOperatorExpressionsByTypes(array $acceptedTypes)
    {
        $operatorExpressions = [];
        foreach ($this->operators as $operator) {
            if ($this->isTypeAccepted($acceptedTypes, $operator->getAcceptedTypes()) === false) {
                continue;
            }
            $operatorExpressions[] = $operator->getExpression();
        }

        return $operatorExpressions;
    }

    /**
     * @return array|string[]
     */
    public function getAvailableComparatorExpressions()
    {
        $comparatorExpressions = [];
        foreach ($this->operators as $operator) {
            $comparatorExpressions[] = $operator->getExpression();
        }
        return $comparatorExpressions;
    }

    /**
     * @param string $withValue
     *
     * @return bool
     */
    protected function isMatchAllValue($withValue)
    {
        if ($withValue === '*') {
            return true;
        }

        return false;
    }
}
