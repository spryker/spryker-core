<?php

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderCommentTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTotalsTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class OrderReader
{
    protected $mapping = [
        'order_reference' => 'SpySalesOrder.OrderReference',
        'customer_reference' => 'SpySalesOrder.CustomerReference',
        'order_created_at' => 'SpySalesOrder.CreatedAt',
        'order_updated_at' => 'SpySalesOrder.UpdatedAt',
        'store' => 'SpySalesOrder.Store',
        'email' => 'SpySalesOrder.Email',
        'salutation' => 'SpySalesOrder.Salutation',
        'first_name' => 'SpySalesOrder.FirstName',
        'last_name' => 'SpySalesOrder.LastName',
        'order_note' => 'SpySalesOrder.CartNote',
        'currency_iso_code' => 'SpySalesOrder.CurrencyIsoCode',
        'price_mode' => 'SpySalesOrder.PriceMode',
        'locale_name' => 'Locale.LocaleName',
        'billing_address_salutation' => 'BillingAddress.Salutation',
        'billing_address_first_name' => 'BillingAddress.FirstName',
        'billing_address_last_name' => 'BillingAddress.LastName',
        'billing_address_middle_name' => 'BillingAddress.MiddleName',
        'billing_address_email' => 'BillingAddress.Email',
        'billing_address_cell_phone' => 'BillingAddress.CellPhone',
        'billing_address_phone' => 'BillingAddress.Phone',
        'billing_address_address1' => 'BillingAddress.Address1',
        'billing_address_address2' => 'BillingAddress.Address2',
        'billing_address_address3' => 'BillingAddress.Address3',
        'billing_address_city' => 'BillingAddress.City',
        'billing_address_zip_code' => 'BillingAddress.ZipCode',
        'billing_address_po_box' => 'BillingAddress.PoBox',
        'billing_address_company' => 'BillingAddress.Company',
        'billing_address_description' => 'BillingAddress.Description',
        'billing_address_comment' => 'BillingAddress.Comment',
        'billing_address_country' => 'Country.Name',
        'billing_address_region' => 'Region.Name',
    ];

    public function sequencialRead(array $exportConfiguration, $offset, $limit) : array
    {
            $orders = SpySalesOrderQuery::create()
                ->joinLocale()
                ->leftJoinBillingAddress()
                ->useBillingAddressQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinCountry()
                    ->leftJoinRegion()
                ->endUse()
                ->offset($offset)
                ->limit($limit);

                if (isset($exportConfiguration['filter_criteria']['locale_is_active'])) {
                    $orders
                        ->useLocaleQuery(null, Criteria::INNER_JOIN)
                            ->filterByIsActive(true)
                        ->endUse();
                }

                if (isset($exportConfiguration['filter_criteria']['store_name'])) {
                    $orders->filterByStore_In($exportConfiguration['filter_criteria']['store_name']);
                }

                if (isset($exportConfiguration['filter_criteria']['order_created_at'])) {
                    $orders->filterByCreatedAt_Between([
                        'min' => $exportConfiguration['filter_criteria']['order_created_at']['from'],
                        'max' => $exportConfiguration['filter_criteria']['order_created_at']['to']
                    ]);
                }

                $orders->select($this->mapping);
                $orders->addAsColumn('order_id', SpySalesOrderTableMap::COL_ID_SALES_ORDER);

                $orders = $orders->find()->toArray();
                $orderIds = [];
                foreach($orders as &$order) {
                    $orderIds[] = $order['order_id'];
                    foreach($this->fieldsToPropel() as $niceName => $propelName) {
                        $order[$niceName] = $order[$propelName];
                        unset($order[$propelName]);
                    }
                }

                $comments = $this->getCommentsByOrderId($orderIds);
                $totals = $this->getTotalsByOrderId($orderIds);
                foreach ($orders as &$order) {
                    $order += [
                        'order_totals_canceled_total' => $totals[$order['order_id']]['CanceledTotal'],
                        'order_totals_discount_total' => $totals[$order['order_id']]['DiscountTotal'],
                        'order_totals_grand_total' => $totals[$order['order_id']]['GrandTotal'],
                        'order_totals_order_expense_total' => $totals[$order['order_id']]['OrderExpenseTotal'],
                        'order_totals_refund_total' => $totals[$order['order_id']]['RefundTotal'],
                        'order_totals_subtotal' => $totals[$order['order_id']]['Subtotal'],
                        'order_totals_tax_total' => $totals[$order['order_id']]['TaxTotal'],
                    ];
                    $order['order_comments'] = json_encode($comments[$order['order_id']] ?? []);

                    unset($order['order_id']);
                }

                return $orders;
    }

    protected function getCommentsByOrderId(array $orderIds): array
    {
        if (count($orderIds) < 1) {
            return [];
        }

        $comments = SpySalesOrderCommentQuery::create()
            ->clearSelectColumns()
            ->addAsColumn('username', SpySalesOrderCommentTableMap::COL_USERNAME)
            ->addAsColumn('message', SpySalesOrderCommentTableMap::COL_MESSAGE)
            ->addAsColumn('created_at', SpySalesOrderCommentTableMap::COL_CREATED_AT)
            ->addAsColumn('updated_at', SpySalesOrderCommentTableMap::COL_UPDATED_AT)
            ->addAsColumn('order_id', SpySalesOrderCommentTableMap::COL_FK_SALES_ORDER)
            ->filterByFkSalesOrder_In($orderIds)
            ->find()->toArray();
        $orderComments = [];
        foreach ($comments as $comment) {
            $orderComments[$comment['order_id']][] =[
                'username' => $comment['Username'],
                'message' => $comment['Message'],
                'created_at' => $comment['CreatedAt'],
                'updated_at' => $comment['UpdatedAt'],
            ];
        }

        return $orderComments;
    }

    protected function getTotalsByOrderId(array $orderIds): array
    {
        if (count($orderIds) < 1) {
            return [];
        }

        $totals = SpySalesOrderTotalsQuery::create()
                    ->clearSelectColumns()
                    ->addAsColumn('totalId', 'MAX(' . SpySalesOrderTotalsTableMap::COL_ID_SALES_ORDER_TOTALS . ')')
                    ->filterByFkSalesOrder_In($orderIds)
                    ->groupByFkSalesOrder()
                    ->find()
                    ->toArray();

        $totalIds = [];
        foreach ($totals as $total) {
            $totalIds[] = $total['IdSalesOrderTotals'];
        }

        $totals = SpySalesOrderTotalsQuery::create()
            ->filterByIdSalesOrderTotals_In($totalIds)
            ->find()
            ->toArray();

        $orderTotals = [];
        foreach ($totals as $total) {
            $orderTotals[$total['FkSalesOrder']] = $total;
        }

        return $orderTotals;
    }

    protected function fieldsToPropel(): array
    {
        return $this->mapping;
    }
}
