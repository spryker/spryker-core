<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

class LogicalComparators
{
    /**
     * @var string
     */
    public const COMPARATOR_AND = 'and';

    /**
     * @var string
     */
    public const COMPARATOR_OR = 'or';

    /**
     * @return array<string>
     */
    public function getLogicalOperators()
    {
        return [
            static::COMPARATOR_AND,
            static::COMPARATOR_OR,
        ];
    }
}
