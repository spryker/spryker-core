<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid\Items;

class DataSource
{

    /** @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery */
    protected $query;

    /** @var int */
    protected $processId;

    /** @var int */
    protected $statusId;

    /** @var string */
    protected $age;

    /**
     * @param null $processId
     * @param null $statusId
     * @param null $age
     * @return $this
     */
    public function setAdditionalParams($processId = null, $statusId = null, $age = null)
    {
        $this->processId = $processId;
        $this->statusId = $statusId;
        $this->age = $age;

        return $this;
    }

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery
     */
    protected function getQuery()
    {
        $age = $this->getOrderStatusAge();
        $this->query = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create()
            ->withColumn(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS, 'item_fk_sales_order_process')
            ->joinOrder()
            ->withColumn(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderTableMap::COL_IS_TEST, 'is_test')
            ->orderByLastStatusChange(\Propel\Runtime\ActiveQuery\Criteria::DESC);

        if (isset($this->processId)) {
            $this->query->filterByFkOmsOrderProcess($this->processId);
        }
        if (isset($this->statusId)) {
            $this->query->filterByFkOmsOrderItemStatus($this->statusId);
        }
        if (isset($this->age)) {
            $this->query->filterByLastStatusChange($age);
        }

        return $this->query;
    }

    protected function getOrderStatusAge()
    {
        $age = null;
        if (isset($this->age)) {
            switch ($this->age) {
                case 'last24h':
                    $age = ['min' => time() - 24 * 60 * 60];
                    break;
                case 'last7d':
                    $age = ['min' => time() - 7 * 24 * 60 * 60, 'max' => time() - 24 * 60 * 60];
                    break;
                case 'before7d':
                    $age = ['max' => time() - 7 * 24 * 60 * 60];
                    break;
            }
        }

        return $age;
    }

}
