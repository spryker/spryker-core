<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\ColumnQueryMapperInterface;
use Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\PaginationQueryMapperInterface;

class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapperInterface
     */
    protected $criteriaMapper;

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\ColumnQueryMapperInterface
     */
    protected $columnMapper;

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\PaginationQueryMapperInterface
     */
    protected $paginationMapper;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\CriteriaMapperInterface $criteriaMapper
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\ColumnQueryMapperInterface $columnMapper
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\Mapper\PaginationQueryMapperInterface $paginationMapper
     */
    public function __construct(
        CriteriaMapperInterface $criteriaMapper,
        ColumnQueryMapperInterface $columnMapper,
        PaginationQueryMapperInterface $paginationMapper
    ) {
        $this->criteriaMapper = $criteriaMapper;
        $this->columnMapper = $columnMapper;
        $this->paginationMapper = $paginationMapper;
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
        $query = $this->mergeQueryWithColumnSelection($query, $propelQueryBuilderCriteriaTransfer);
        $query = $this->mergeQueryWithPagination($query, $propelQueryBuilderCriteriaTransfer);

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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mergeQueryWithColumnSelection(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        if ($propelQueryBuilderCriteriaTransfer->getColumnSelection()) {
            $query = $this->columnMapper->mapColumns($query, $propelQueryBuilderCriteriaTransfer->getColumnSelection());
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mergeQueryWithPagination(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        if ($propelQueryBuilderCriteriaTransfer->getPagination()) {
            $query = $this->paginationMapper->mapPagination($query, $propelQueryBuilderCriteriaTransfer->getPagination());
        }

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
