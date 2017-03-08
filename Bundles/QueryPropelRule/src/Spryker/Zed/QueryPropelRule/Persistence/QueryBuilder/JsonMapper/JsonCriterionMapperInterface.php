<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface;

interface JsonCriterionMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     *
     * @return string|null
     */
    public function getAttributeName(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer);

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     *
     * @return bool
     */
    public function isJsonAttribute(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @throws \Spryker\Zed\QueryPropelRule\Persistence\Exception\QueryBuilderException
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    public function createJsonCriterion(ModelCriteria $criteria, PropelQueryBuilderRuleSetTransfer $ruleSetTransfer, OperatorInterface $operator);

}
