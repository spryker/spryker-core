<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class IsNotIn extends AbstractComparator implements ComparatorInterface
{
    /**
     * @var string
     */
    protected const EXPRESSION = 'is not in';

    /**
     * @var bool
     */
    protected const ALLOW_EMPTY_VALUE = true;

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

        $searchValues = $this->getExplodedListValue((string)$withValue);
        $clauseValues = $this->getExplodedListValue((string)$clauseTransfer->getValue());

        return array_intersect($searchValues, $clauseValues) === [];
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            ComparatorOperators::TYPE_LIST,
        ];
    }
}
