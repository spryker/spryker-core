<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator;

use Propel\Runtime\ActiveQuery\Criteria;

class NotContains extends Contains
{
    public const TYPE = 'not_contains';

    /**
     * @return string
     */
    public function getOperator()
    {
        return Criteria::NOT_LIKE;
    }
}
