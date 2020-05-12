<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper;

class SalesExpenseMapper
{
    /**
     * @var array
     */
    protected $csvMapping = [
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
     * @return array
     */
    public function getCsvMapping(): array
    {
        return $this->csvMapping;
    }

    /**
     * @param array $salesOrderData
     *
     * @return array
     */
    public function mapSalesExpenseDataToCsvFormattedArray(array $salesOrderData): array
    {
        $csvHeader = $this->getCsvHeader();
        $salesOrderCsvFormattedData = [];
        foreach ($salesOrderData as $salesOrderRow) {
            $salesOrderCsvFormattedData[] = array_combine($csvHeader, $salesOrderRow);
        }

        return $salesOrderCsvFormattedData;
    }

    /**
     * @return string[]
     */
    protected function getCsvHeader(): array
    {
        return array_keys($this->csvMapping);
    }
}
