<?php

namespace SprykerFeature\Zed\Oms\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLogQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsTransitionLogTableMap;
use DateTime;

class OmsQueryContainer extends AbstractQueryContainer
{
    /**
     * @param $states
     * @param $processName
     *
     * @return ModelCriteria
     */
    public function getOrderItemsByStatus($states, $processName)
    {
        return SpySalesOrderItemQuery::create()
            ->joinProcess(null, $joinType = Criteria::INNER_JOIN)
            ->joinProcess(null, $joinType = Criteria::INNER_JOIN)
            ->where("Process.name = ?", $processName)
            ->where("Status.name IN ('" . implode("', '", $states) . "')");
    }

    /**
     * @param SpySalesOrder $order
     *
     * @return ModelCriteria
     * @throws PropelException
     */
    public function getLogForOrder(SpySalesOrder $order)
    {
        return SpyOmsTransitionLogQuery::create()
            ->filterByOrder($order)
            ->orderBy(SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
    }

    /**
     * @param DateTime $now
     *
     * @return ModelCriteria
     */
    public function findItemsWithExpiredTimeouts(DateTime $now)
    {
        return SpySalesOrderItemQuery::create()
            ->joinEventTimeout()
            ->where('EventTimeout.timeout < ?', $now)
            ->withColumn('EventTimeout.event', 'event');
    }

    /**
     * @param StatusInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItemQuery
     * @throws PropelException
     */
    public function countOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query = SpySalesOrderItemQuery::create();
        $query->withColumn('COUNT(*)', 'Count')->select(['Count']);

        if ($returnTest === false) {
            $query->useOrderQuery()->filterByIsTest(false)->endUse();
        }

        $stateNames = [];
        foreach ($states as $status) {
            $stateNames[] = $status->getName();
        }

        $query->useStatusQuery()->filterByName($stateNames)->endUse();
        $query->filterBySku($sku);

        return $query;
    }

    /**
     * @param StatusInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItemQuery
     */
    public function getOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query = SpySalesOrderItemQuery::create();

        if ($returnTest === false) {
            $query->useOrderQuery()->filterByIsTest(false)->endUse();
        }

        $stateNames = [];
        foreach ($states as $status) {
            $stateNames[] = $status->getName();
        }

        $query->useStatusQuery()->filterByName($stateNames)->endUse();
        $query->filterBySku($sku);

        return $query;
    }
}
