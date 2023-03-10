<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Comparator;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class DoesNotContain extends AbstractComparator implements ComparatorInterface
{
    /**
     * @var string
     */
    protected const EXPRESSION = 'does not contain';

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

        return (stripos(trim($withValue), $clauseTransfer->getValue()) === false);
    }

    /**
     * @return list<string>
     */
    public function getAcceptedTypes(): array
    {
        return [
            ComparatorOperators::TYPE_STRING,
            ComparatorOperators::TYPE_NUMBER,
        ];
    }
}
