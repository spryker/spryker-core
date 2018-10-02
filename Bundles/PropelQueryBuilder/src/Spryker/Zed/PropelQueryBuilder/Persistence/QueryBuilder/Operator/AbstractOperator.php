<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;

abstract class AbstractOperator implements OperatorInterface
{
    public const TYPE = null;

    /**
     * @return string
     */
    abstract public function getOperator();

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $rule
     *
     * @return mixed
     */
    public function getValue(PropelQueryBuilderRuleSetTransfer $rule)
    {
        return $rule->getValue();
    }
}
