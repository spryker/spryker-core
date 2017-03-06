<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator;

use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Propel\Runtime\ActiveQuery\Criteria;

class Contains extends AbstractOperator
{

    const TYPE = 'contains';

    /**
     * @return string
     */
    public function getOperator()
    {
        return Criteria::LIKE;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return mixed
     */
    public function getValue(RuleQuerySetTransfer $rule)
    {
        return sprintf('%%%s%%', $rule->getValue());
    }

}
