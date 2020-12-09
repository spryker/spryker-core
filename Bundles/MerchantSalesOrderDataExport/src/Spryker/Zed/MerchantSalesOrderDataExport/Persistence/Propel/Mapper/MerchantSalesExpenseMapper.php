<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

class MerchantSalesExpenseMapper
{
    /**
     * @phpstan-var array<string, string>
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'merchant_order_reference' => 'SpyMerchantSalesOrder.MerchantSalesOrderReference',
        'marketplace_order_reference' => 'SpySalesOrder.OrderReference',
        'shipment_id' => 'SpySalesShipment.IdSalesShipment',
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
     * @param array $merchantSalesExpenseRows
     *
     * @return array
     */
    public function mapMerchantSalesExpenseDataByField(array $merchantSalesExpenseRows): array
    {
        $fields = $this->getFields();
        $selectedFields = array_values(array_intersect_key($fields, $merchantSalesExpenseRows[0] ?? []));

        $mappedMerchantSalesExpenses = [];
        foreach ($merchantSalesExpenseRows as $merchantSalesExpenseRow) {
            $mappedMerchantSalesExpenses[] = array_combine($selectedFields, $merchantSalesExpenseRow);
        }

        return $mappedMerchantSalesExpenses;
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
