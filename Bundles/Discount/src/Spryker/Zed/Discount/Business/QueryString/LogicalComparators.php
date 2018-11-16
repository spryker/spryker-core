<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

class LogicalComparators
{
    public const COMPARATOR_AND = 'and';
    public const COMPARATOR_OR = 'or';

    /**
     * @return string[]
     */
    public function getLogicalOperators()
    {
        return [
            self::COMPARATOR_AND,
            self::COMPARATOR_OR,
        ];
    }
}
