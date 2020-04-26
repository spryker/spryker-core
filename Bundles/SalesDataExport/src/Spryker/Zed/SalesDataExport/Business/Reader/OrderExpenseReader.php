<?php

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

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


                if (isset($exportConfiguration['filter_criteria']['store_name'])) {
                    $orderExpenses
                        ->useOrderQuery()
                            ->filterByStore_In($exportConfiguration['filter_criteria']['store_name'])
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

                $orderExpenses->select($this->mapping);

                $orderExpenses = $orderExpenses->find()->toArray();
                foreach($orderExpenses as &$orderExpense) {
                    foreach($this->fieldsToPropel() as $niceName => $propelName) {
                        $orderExpense[$niceName] = $orderExpense[$propelName];
                        unset($orderExpense[$propelName]);
                    }
                }

                return $orderExpenses;
    }

    protected function fieldsToPropel(): array
    {
        return $this->mapping;
    }
}
