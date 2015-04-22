<?php

namespace SprykerFeature\Zed\Oms\Persistence;

use Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;

/**
 * Class OmsQueryContainer
 *
 * @package SprykerFeature\Zed\Oms
 */
class OmsQueryContainer extends AbstractQueryContainer
{
    /**
     * @param $states
     * @param $processName
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getOrderItemsByStatus($states, $processName)
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create()
            ->joinProcess(null, $joinType = \Propel\Runtime\ActiveQuery\Criteria::INNER_JOIN)
            ->joinProcess(null, $joinType = \Propel\Runtime\ActiveQuery\Criteria::INNER_JOIN)
            ->where("Process.name = ?", $processName)
            ->where("Status.name IN ('" . implode("', '", $states) . "')");
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getLogForOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        return \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLogQuery::create()
            ->filterByOrder($order)
            ->orderBy(\SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
    }

    /**
     * @param \DateTime $now
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function findItemsWithExpiredTimeouts(\DateTime $now)
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create()
            ->joinEventTimeout()
            ->where('EventTimeout.timeout < ?', $now)
            ->withColumn('EventTimeout.event', 'event');
    }

    /**
     * @param StatusInterface[] $states
     * @param string            $sku
     * @param bool              $returnTest
     *
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create();
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
     * @param string            $sku
     * @param bool              $returnTest
     *
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery
     */
    public function getOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query =\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create();

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
