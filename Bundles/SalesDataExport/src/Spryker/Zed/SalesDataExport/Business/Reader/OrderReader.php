<?php

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class OrderReader
{
    public function sequencialRead(array $exportConfiguration, $offset, $limit) : array
    {
            $orders = SpySalesOrderQuery::create()
                ->clearSelectColumns()
                ->select(
                    $this->fieldsToPropel($exportConfiguration)
                )
                ->joinWithLocale()
                ->leftJoinWithBillingAddress()
                ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithCountry()
                    ->leftJoinWithRegion()
                ->endUse()
                ->offset($offset)
                ->limit($limit);

                if (isset($exportConfiguration['filters']['spy_locale.is_active'])) {
                    $orders
                        ->useLocaleQuery(null, Criteria::INNER_JOIN)
                            ->filterByIsActive(true)
                        ->endUse();
                }



                $orders = $orders->find()->toArray();
                /*$orderIds = $this->filterIds($orders);
                $orderIds = [1];

                $comments = SpySalesOrderCommentQuery::create()
                    ->filterByFkSalesOrder_In($orderIds)
                    ->find();

                $totalIds = SpySalesOrderTotalsQuery::create()
                    ->clearSelectColumns()
                    ->addAsColumn('maxi', 'MAX(id_total)')
                    ->filterByFkSalesOrder_In($orderIds)
                    ->groupByFkSalesOrder()
                    ->find();

                $totals = SpySalesOrderTotalsQuery::create()
                    ->filterByIdSalesOrderTotals($totalIds)
                    ->find();
        */

                return $orders;
    }

    protected function fieldsToPropel(array $exportConfiguraiton): array
    {
        return [
            'SpySalesOrder.Store',
            'SpySalesOrder.OrderReference',
            'SpySalesOrder.CreatedAt',
            'Locale.LocaleName',
            'BillingAddress.FirstName',
            'Country.Name',
            'Region.Name',
//            'order_totals_grand_total' => [],
  //          'order_comments' => ['format' => 'json-list'],
    //        'order_item_id' => ['format' => 'comma-separated-list'],
        ];
    }
}
