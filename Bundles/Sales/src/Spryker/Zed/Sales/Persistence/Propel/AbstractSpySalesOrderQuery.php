<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel;

use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery as BaseSpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTotalsTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpySalesOrderQuery extends BaseSpySalesOrderQuery
{

    /**
     * @param string $resultFieldName
     *
     * @return $this
     */
    public function addItemStateNameAggregationToResult($resultFieldName)
    {
        $subQuery = clone $this;
        $subQuery->clear();
        $subQuery
            ->setModelAlias('sso', true)
            ->useItemQuery()
                ->joinWithState()
                ->addSelfSelectColumns()
                ->clearSelectColumns()
                ->withColumn(
                    sprintf('GROUP_CONCAT(%s)', SpyOmsOrderItemStateTableMap::COL_NAME),
                    $resultFieldName
                )
                ->filterByFkSalesOrder(
                    sprintf(
                        '%s = %s',
                        SpySalesOrderItemTableMap::COL_FK_SALES_ORDER,
                        SpySalesOrderTableMap::COL_ID_SALES_ORDER
                    ),
                    Criteria::CUSTOM
                )
                ->groupByFkSalesOrder()
            ->endUse();

        $this->addSubQueryResultField($subQuery, $resultFieldName);

        return $this;
    }

    /**
     * @param string $resultFieldName
     *
     * @return $this
     */
    public function addItemCountToResult($resultFieldName)
    {
        $subQuery = clone $this;
        $subQuery->clear();
        $subQuery
            ->setModelAlias('sso', true)
            ->useItemQuery()
                ->withColumn(
                    sprintf('COUNT(%s)', SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM),
                    $resultFieldName
                )
                ->filterByFkSalesOrder(
                    sprintf(
                        '%s = %s',
                        SpySalesOrderItemTableMap::COL_FK_SALES_ORDER,
                        SpySalesOrderTableMap::COL_ID_SALES_ORDER
                    ),
                    Criteria::CUSTOM
                )
                ->groupByFkSalesOrder()
            ->endUse();

        $this->addSubQueryResultField($subQuery, $resultFieldName);

        return $this;
    }

    /**
     * @param string $resultFieldName
     *
     * @return $this
     */
    public function addLastOrderGrandTotalToResult($resultFieldName)
    {
        $subQuery = clone $this;
        $subQuery->clear();

        $subQuery
            ->setModelAlias('sso', true)
            ->useOrderTotalQuery()
            ->withColumn(
                SpySalesOrderTotalsTableMap::COL_GRAND_TOTAL,
                $resultFieldName
            )
            ->filterByFkSalesOrder(
                sprintf(
                    '%s = %s',
                    SpySalesOrderTotalsTableMap::COL_FK_SALES_ORDER,
                    SpySalesOrderTableMap::COL_ID_SALES_ORDER
                ),
                Criteria::CUSTOM
            )
            ->limit(1)
            ->orderByCreatedAt(Criteria::DESC)
            ->endUse();

        $this->addSubQueryResultField($subQuery, $resultFieldName);

        return $this;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $subQuery
     * @param string $resultFieldName
     *
     * @return void
     */
    protected function addSubQueryResultField(ModelCriteria $subQuery, $resultFieldName)
    {
        $params = [];
        $this->withColumn(
            sprintf('(%s)', $subQuery->createSelectSql($params)),
            $resultFieldName
        );
    }

    /**
     * @param int $idOrderProcess
     *
     * @return $this
     */
    public function filterByIdItemOrderProcess($idOrderProcess)
    {
        return $this
            ->useItemQuery()
                ->filterByFkOmsOrderProcess($idOrderProcess)
            ->endUse()
            ->groupByIdSalesOrder();
    }

    /**
     * @param int $idItemState
     *
     * @return $this
     */
    public function filterByIdItemState($idItemState)
    {
        return $this
            ->useItemQuery()
                ->filterByFkOmsOrderItemState($idItemState)
            ->endUse()
            ->groupByIdSalesOrder();
    }

    /**
     * @param mixed $lastStateChange The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent. Add Criteria::IN explicitly.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals. Add SprykerCriteria::BETWEEN explicitly.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this
     */
    public function filterByLastItemStateChange($lastStateChange, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useItemQuery()
                ->filterByLastStateChange($lastStateChange, $comparison)
            ->endUse()
            ->groupByIdSalesOrder();
    }

} // SpySalesOrderQuery
