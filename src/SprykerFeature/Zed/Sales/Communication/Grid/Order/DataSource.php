<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid\Order;

class DataSource
{

    /**
     * @return array|\PropelObjectCollection|void
     */
    public function getData()
    {
        $data = parent::getData();
        foreach ($data as $row => $value) {
            $data[$row]['is_test'] = ($value['is_test'] ? 'icon-check' : 'icon-check-empty');
            $comment = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery::create()->filterByFkSalesOrder(
                $data[$row]['id_sales_order']
            )->orderByUpdatedAt(\Propel\Runtime\ActiveQuery\Criteria::DESC)->findOne();
            if ($comment) {
                $data[$row]['message'] = $comment->getMessage();
            }
        }

        return $data;
    }

    /**
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function formatFilterValue($field, $value)
    {
        switch ($field) {
            case 'grand_total':
                return $value * 100;
            default:
                return $value;
        }
    }

    /**
     * @param string $field
     * @param $value
     * @param $row
     * @return float|mixed
     */
    public function formatOutputValue($field, $value, $row)
    {
        switch ($field) {
            case 'grand_total':
                return $value / 100;
            default:
                return $value;
        }
    }

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery
     */
    protected function getQuery()
    {
        $statusColumn = \SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsOrderItemStatusTableMap::COL_NAME;
        $processColumn = \SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsOrderProcessTableMap::COL_NAME;
        $query = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()
            ->joinPayment(null, \Propel\Runtime\ActiveQuery\Criteria::LEFT_JOIN)
            ->withColumn(\SprykerFeature\Zed\Payment\Persistence\Propel\Map\PacPaymentTableMap::COL_METHOD, 'payment_method')
            ->withColumn(\SprykerFeature\Zed\Payment\Persistence\Propel\Map\PacPaymentTableMap::COL_PROVIDER, 'payment_provider')
            ->joinShippingAddress()->withColumn(
                \SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderAddressTableMap::COL_FIRST_NAME,
                'shipping_firstname'
            )->withColumn(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderAddressTableMap::COL_LAST_NAME, 'shipping_lastname')
            ->useItemQuery()
            ->joinProcess()
            ->joinStatus()
            ->endUse()
            ->addGroupByColumn(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderTableMap::COL_ID_SALES_ORDER)
            ->withColumn("GROUP_CONCAT(DISTINCT $statusColumn ORDER BY $statusColumn SEPARATOR ', ')", 'status')
            ->withColumn(
                "GROUP_CONCAT(DISTINCT $processColumn ORDER BY $processColumn SEPARATOR ', ')",
                'process_name'
            );

        return $query;
    }

}
