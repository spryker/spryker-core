<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator;

use Propel\Runtime\ActiveQuery\Criteria;

class NotIn extends In
{
    public const TYPE = 'not_in';

    /**
     * @return string
     */
    public function getOperator()
    {
        return Criteria::NOT_IN;
    }
}
