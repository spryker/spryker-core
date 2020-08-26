<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\QueryBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\CustomCriterion;

class OrderSearchQueryJoinQueryBuilder implements OrderSearchQueryJoinQueryBuilderInterface
{
    protected const CONCAT = 'CONCAT';

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function addSalesOrderQueryFilters(
        SpySalesOrderQuery $salesOrderQuery,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): SpySalesOrderQuery {
        $whereConditionGroups = [];

        foreach ($queryJoinCollectionTransfer->getQueryJoins() as $queryJoinTransfer) {
            $salesOrderQuery = $this->processQueryJoin(
                $salesOrderQuery,
                $queryJoinTransfer,
                $whereConditionGroups
            );
        }

        if ($whereConditionGroups) {
            $salesOrderQuery->where($whereConditionGroups, Criteria::LOGICAL_AND);
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     * @param string[] $whereConditionGroups
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function processQueryJoin(
        SpySalesOrderQuery $salesOrderQuery,
        QueryJoinTransfer $queryJoinTransfer,
        array &$whereConditionGroups
    ): SpySalesOrderQuery {
        $salesOrderQuery = $this->addSalesOrderQueryJoin($salesOrderQuery, $queryJoinTransfer);

        if ($queryJoinTransfer->getWithColumns()) {
            $salesOrderQuery = $this->addSalesOrderQueryWithColumns(
                $salesOrderQuery,
                $queryJoinTransfer->getWithColumns()
            );
        }

        if ($queryJoinTransfer->getWhereConditions()->count()) {
            $salesOrderQuery = $this->addSalesOrderQueryWhereConditionGroup(
                $salesOrderQuery,
                $queryJoinTransfer->getWhereConditions(),
                $whereConditionGroups
            );
        }

        if ($queryJoinTransfer->getOrderBy()) {
            $salesOrderQuery->orderBy(
                $queryJoinTransfer->getOrderBy(),
                $queryJoinTransfer->getOrderDirection() ?? Criteria::DESC
            );
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param string[] $withColumns
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSalesOrderQueryWithColumns(
        SpySalesOrderQuery $salesOrderQuery,
        array $withColumns
    ): SpySalesOrderQuery {
        foreach ($withColumns as $name => $withColumn) {
            $salesOrderQuery->withColumn($withColumn, $name);
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSalesOrderQueryJoin(
        SpySalesOrderQuery $salesOrderQuery,
        QueryJoinTransfer $queryJoinTransfer
    ): SpySalesOrderQuery {
        if ($queryJoinTransfer->getRelation()) {
            return $this->addSalesOrderQueryRelationJoin($salesOrderQuery, $queryJoinTransfer);
        }

        $left = $queryJoinTransfer->getLeft();
        $right = $queryJoinTransfer->getRight();

        if ($left && $right) {
            $salesOrderQuery->addJoin(
                $left,
                $right,
                $queryJoinTransfer->getJoinType() ?? Criteria::LEFT_JOIN
            );
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSalesOrderQueryRelationJoin(
        SpySalesOrderQuery $salesOrderQuery,
        QueryJoinTransfer $queryJoinTransfer
    ): SpySalesOrderQuery {
        $salesOrderQuery->join(
            $queryJoinTransfer->getRelation(),
            $queryJoinTransfer->getJoinType() ?? Criteria::LEFT_JOIN
        );

        if ($queryJoinTransfer->getCondition()) {
            $salesOrderQuery->addJoinCondition(
                $queryJoinTransfer->getRelation(),
                $queryJoinTransfer->getCondition()
            );
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \ArrayObject|\Generated\Shared\Transfer\QueryWhereConditionTransfer[] $queryWhereConditionTransfers
     * @param string[] $conditionGroups
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addSalesOrderQueryWhereConditionGroup(
        SpySalesOrderQuery $salesOrderQuery,
        ArrayObject $queryWhereConditionTransfers,
        array &$conditionGroups
    ): SpySalesOrderQuery {
        $conditionGroupName = uniqid('', true);

        $conditions = $this->createSalesOrderQueryWhereConditions($salesOrderQuery, $queryWhereConditionTransfers);

        if ($conditions) {
            $salesOrderQuery->combine(
                $conditions,
                Criteria::LOGICAL_OR,
                $conditionGroupName
            );

            $conditionGroups[] = $conditionGroupName;
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \ArrayObject|\Generated\Shared\Transfer\QueryWhereConditionTransfer[] $queryWhereConditionTransfers
     *
     * @return string[]
     */
    protected function createSalesOrderQueryWhereConditions(
        SpySalesOrderQuery $salesOrderQuery,
        ArrayObject $queryWhereConditionTransfers
    ): array {
        $conditions = [];

        foreach ($queryWhereConditionTransfers as $queryWhereConditionTransfer) {
            $conditionName = uniqid($queryWhereConditionTransfer->getColumn(), true);
            $salesOrderQuery = $this->addConditionToQuery($conditionName, $queryWhereConditionTransfer, $salesOrderQuery);

            $combineWithCondition = $queryWhereConditionTransfer->getMergeWithCondition();

            if ($combineWithCondition) {
                $salesOrderQuery->combine(
                    [$combineWithCondition, $conditionName],
                    $queryWhereConditionTransfer->getMergeOperator() ?? Criteria::LOGICAL_OR,
                    $combineWithCondition
                );

                continue;
            }

            $conditions[] = $conditionName;
        }

        return $conditions;
    }

    /**
     * @param string $conditionName
     * @param \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function addConditionToQuery(
        string $conditionName,
        QueryWhereConditionTransfer $queryWhereConditionTransfer,
        SpySalesOrderQuery $salesOrderQuery
    ): SpySalesOrderQuery {
        $column = $queryWhereConditionTransfer->getColumn();
        $value = $queryWhereConditionTransfer->getValue();
        $comparison = $queryWhereConditionTransfer->getComparison() ?? Criteria::LIKE;

        if (mb_strpos($column, static::CONCAT) !== false) {
            return $salesOrderQuery->addCond(
                $conditionName,
                new CustomCriterion(new Criteria(), $column . $comparison . sprintf('\'%%%s%%\'', $value)),
                null,
                Criteria::CUSTOM
            );
        }

        return $salesOrderQuery->addCond(
            $conditionName,
            $column,
            $comparison === Criteria::LIKE ? $this->formatFilterValue($value) : $value,
            $comparison
        );
    }

    /**
     * @param string|null $value
     *
     * @return string
     */
    protected function formatFilterValue(?string $value): string
    {
        return sprintf('%%%s%%', $value);
    }
}
