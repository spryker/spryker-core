<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper;

class SalesExpenseMapper
{
    /**
     * @phpstan-var array<string, string>
     *
     * @var string[]
     */
    protected $fieldMapping = [
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

    /**
     * @phpstan-return array<string, string>
     *
     * @return string[]
     */
    public function getFieldMapping(): array
    {
        return $this->fieldMapping;
    }

    /**
     * @param array $salesExpenseRows
     *
     * @return array
     */
    public function mapSalesExpenseDataByField(array $salesExpenseRows): array
    {
        $fields = $this->getFields();
        $selectedFields = array_values(array_intersect_key($fields, $salesExpenseRows[0] ?? []));

        $mappedSalesExpenses = [];
        foreach ($salesExpenseRows as $salesExpenseRow) {
            $mappedSalesExpenses[] = array_combine($selectedFields, $salesExpenseRow);
        }

        return $mappedSalesExpenses;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @return string[]
     */
    protected function getFields(): array
    {
        return array_flip($this->fieldMapping);
    }
}
