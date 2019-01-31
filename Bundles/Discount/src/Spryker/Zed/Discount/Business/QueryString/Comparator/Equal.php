<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class Equal implements ComparatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface
     */
    protected $floatRounder;

    /**
     * @param \Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface $floatRounder
     */
    public function __construct(FloatRounderInterface $floatRounder)
    {
        $this->floatRounder = $floatRounder;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $withValue
     *
     * @return bool
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue)
    {
        $this->isValidValue($withValue);

        if (is_numeric($clauseTransfer->getValue()) && is_numeric($withValue)) {
             return $this->compareNumbers($clauseTransfer->getValue(), $withValue);
        }

        return strcasecmp($clauseTransfer->getValue(), $withValue) === 0;
    }

    /**
     * @param float $clauseValue
     * @param float $withValue
     *
     * @return bool
     */
    protected function compareNumbers(float $clauseValue, float $withValue): bool
    {
        return $this->floatRounder->round($clauseValue) ===
            $this->floatRounder->round($withValue);
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
        return '=';
    }

    /**
     * @return string[]
     */
    public function getAcceptedTypes()
    {
        return [
            ComparatorOperators::TYPE_NUMBER,
            ComparatorOperators::TYPE_STRING,
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
        if (!is_scalar($withValue)) {
            throw new ComparatorException('Only scalar value can be used together with "=" comparator.');
        }

        return true;
    }
}
