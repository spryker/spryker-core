<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\QueryBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class OrderSearchQueryJoinQueryBuilder implements OrderSearchQueryJoinQueryBuilderInterface
{
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
            $salesOrderQuery = $this->addSalesOrderQueryJoin($salesOrderQuery, $queryJoinTransfer);

            if ($queryJoinTransfer->getQueryWhereConditions()->count()) {
                $salesOrderQuery = $this->addSalesOrderQueryWhereConditionGroup(
                    $salesOrderQuery,
                    $queryJoinTransfer->getQueryWhereConditions(),
                    $whereConditionGroups
                );
            }
        }

        if ($whereConditionGroups) {
            $salesOrderQuery->where($whereConditionGroups, Criteria::LOGICAL_AND);
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
     * @param array $conditionGroups
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
            $column = $queryWhereConditionTransfer->getColumn();
            $value = $queryWhereConditionTransfer->getValue();

            $conditionName = uniqid($column, true);
            $comparison = $queryWhereConditionTransfer->getComparison() ?? Criteria::ILIKE;

            $salesOrderQuery->addCond(
                $conditionName,
                $column,
                $comparison === Criteria::ILIKE ? sprintf('%%%s%%', $value) : $value,
                $comparison
            );

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
}
