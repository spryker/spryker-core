<?php

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;

class OrderExpenseReader
{
    protected $mapping = [
        'order_reference' => 'Order.OrderReference',
        'order_shipment_id' => 'SpySalesShipment.IdSalesShipment',
        'canceled_amount' => 'SpySalesExpense.CanceledAmount',
        'discount_amount_aggregation' => 'SpySalesExpense.DiscountAmountAggregation',
        'gross_price' => 'SpySalesExpense.GrossPrice',
        'name' => 'SpySalesExpense.Name',
        'net_price' => 'SpySalesExpense.NetPrice',
        'price' => 'SpySalesExpense.Price',
        'price_to_pay_aggregation' => 'SpySalesExpense.PriceToPayAggregation',
        'refundable_amount' => 'SpySalesExpense.RefundableAmount',
        'tax_amount' => 'SpySalesExpense.TaxAmount',
        'tax_amount_after_cancellation' => 'SpySalesExpense.TaxAmountAfterCancellation',
        'tax_rate' => 'SpySalesExpense.TaxRate',
        'type' => 'SpySalesExpense.Type',
        'expense_created_at' => 'SpySalesExpense.CreatedAt',
        'expense_updated_at' => 'SpySalesExpense.UpdatedAt',
    ];

    public function csvReadBatch(array $exportConfiguration, $offset, $limit) : array
    {
            $orderExpenses = SpySalesExpenseQuery::create()
                ->joinOrder()
                ->leftJoinSpySalesShipment()
                ->offset($offset)
                ->limit($limit);

            if (isset($exportConfiguration['filter_criteria']['order_store'])) {
                $orderExpenses
                    ->useOrderQuery()
                        ->filterByStore_In($exportConfiguration['filter_criteria']['order_store'])
                    ->endUse();
            }

            if (isset($exportConfiguration['filter_criteria']['order_created_at'])) {
                $orderExpenses
                    ->useOrderQuery()
                        ->filterByCreatedAt_Between([
                            'min' => $exportConfiguration['filter_criteria']['order_created_at']['from'],
                            'max' => $exportConfiguration['filter_criteria']['order_created_at']['to']
                        ])
                    ->endUse();
            }

            $selectedFields = array_intersect_key($this->mapping, array_flip($exportConfiguration['fields']));
            $orderExpenses->select($selectedFields);
            $orderExpenses = $orderExpenses->find()->toArray();

            // This will need a much efficient solution
            foreach($orderExpenses as &$orderExpense) {
                foreach($selectedFields as $niceName => $propelName) {
                    $orderExpense[$niceName] = $orderExpense[$propelName];
                    unset($orderExpense[$propelName]);
                }
            }

            return [
                count($orderExpenses) > 0 ? array_keys($orderExpenses[0]) : [],
                $orderExpenses,
            ];
    }
}
