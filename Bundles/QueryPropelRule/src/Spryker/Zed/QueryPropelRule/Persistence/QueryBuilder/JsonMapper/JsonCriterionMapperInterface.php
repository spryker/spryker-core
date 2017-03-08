<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper;

use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface;

interface JsonCriterionMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return string|null
     */
    public function getAttributeName(RuleQuerySetTransfer $rule);

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return bool
     */
    public function isJsonAttribute(RuleQuerySetTransfer $rule);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @throws \Spryker\Zed\QueryPropelRule\Persistence\Exception\QueryBuilderException
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    public function createJsonCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $rule, OperatorInterface $operator);

}
