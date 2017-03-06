<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder;

use Generated\Shared\Transfer\RuleQueryTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class QueryBuilder implements QueryBuilderInterface
{

    /**
     * @var \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\CriteriaMapperInterface
     */
    protected $criteriaMapper;

    /**
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\CriteriaMapperInterface $criteriaMapper
     */
    public function __construct(CriteriaMapperInterface $criteriaMapper)
    {
        $this->criteriaMapper = $criteriaMapper;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(ModelCriteria $query, RuleQueryTransfer $ruleQueryTransfer)
    {
        $query = $this->mergeQueryWithCriteria($query, $ruleQueryTransfer);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mergeQueryWithCriteria(ModelCriteria $query, RuleQueryTransfer $ruleQueryTransfer)
    {
        $criteria = $this->toCriteria($ruleQueryTransfer);
        $query->mergeWith($criteria, $ruleQueryTransfer->getRuleSet()->getCondition());

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function toCriteria(RuleQueryTransfer $ruleQueryTransfer)
    {
        return $this->criteriaMapper->toCriteria($ruleQueryTransfer);
    }

}
