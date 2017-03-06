<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper;

use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface;

interface JsonMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return string
     */
    public function getField(RuleQuerySetTransfer $rule, OperatorInterface $operator, $attributeName);

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return mixed
     */
    public function getValue(RuleQuerySetTransfer $rule, OperatorInterface $operator, $attributeName);

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return string
     */
    public function getOperator(RuleQuerySetTransfer $rule, OperatorInterface $operator, $attributeName);

}
