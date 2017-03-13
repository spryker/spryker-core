<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class QueryBuilder implements QueryBuilderInterface
{

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapperInterface
     */
    protected $criteriaMapper;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapperInterface $criteriaMapper
     */
    public function __construct(CriteriaMapperInterface $criteriaMapper)
    {
        $this->criteriaMapper = $criteriaMapper;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        $propelQueryBuilderCriteriaTransfer->requireRuleSet();
        $query = $this->mergeQueryWithCriteria($query, $propelQueryBuilderCriteriaTransfer);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mergeQueryWithCriteria(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        $criteria = $this->toCriteria($propelQueryBuilderCriteriaTransfer);
        $query->mergeWith($criteria, $propelQueryBuilderCriteriaTransfer->getRuleSet()->getCondition());

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function toCriteria(PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        return $this->criteriaMapper->toCriteria($propelQueryBuilderCriteriaTransfer);
    }

}
