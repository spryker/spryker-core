<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class LessEqual extends AbstractComparator implements ComparatorInterface
{
    /**
     * @var string
     */
    protected const EXPRESSION = '<=';

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param mixed $withValue
     *
     * @return bool
     */
    public function compare(ClauseTransfer $clauseTransfer, $withValue): bool
    {
        if (!$this->isValidValue($withValue)) {
            return false;
        }

        return $withValue <= $clauseTransfer->getValue();
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            ComparatorOperators::TYPE_NUMBER,
        ];
    }

    /**
     * @param mixed $withValue
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isValidValue($withValue): bool
    {
        if (!parent::isValidValue($withValue)) {
            return false;
        }

        if (preg_match(ComparatorOperators::NUMBER_REGEXP, $withValue) === 0) {
            throw new ComparatorException('Only numeric value can be used together with "<=" comparator.');
        }

        return true;
    }
}
