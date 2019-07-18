<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Propel\Runtime\ActiveQuery\Criteria;

class In extends AbstractOperator
{
    public const TYPE = 'in';

    /**
     * @return string
     */
    public function getOperator()
    {
        return Criteria::IN;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $rule
     *
     * @return mixed
     */
    public function getValue(PropelQueryBuilderRuleSetTransfer $rule)
    {
        $value = trim($rule->getValue());
        if ($value) {
            return explode(',', $value);
        }

        return $rule->getValue();
    }
}
