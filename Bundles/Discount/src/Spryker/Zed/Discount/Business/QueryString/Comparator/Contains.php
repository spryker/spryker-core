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

class Contains implements ComparatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface $floatRounder
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
        $clauseValue = $clauseTransfer->getValue();

        if (is_numeric($withValue) && is_numeric($clauseTransfer->getValue())) {
            $withValue = $this->floatRounder->round($withValue);
            $clauseValue = $this->floatRounder->round($clauseValue);
        }

        return (stripos(trim($withValue), $clauseValue) !== false);
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
        return 'contains';
    }

    /**
     * @return string[]
     */
    public function getAcceptedTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING,
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
        if (!is_scalar($withValue)) {
            throw new ComparatorException('Only scalar value can be used together with "contains" comparator.');
        }

        return true;
    }
}
