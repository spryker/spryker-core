<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use RuntimeException;

abstract class AbstractOperator implements OperatorInterface
{
    /**
     * @var string|null
     */
    public const TYPE = null;

    /**
     * @return string
     */
    abstract public function getOperator();

    /**
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getType()
    {
        $type = static::TYPE;
        if ($type === null) {
            throw new RuntimeException('Type not defined');
        }

        return $type;
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
