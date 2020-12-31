<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;

class MerchantSalesExpenseMapper
{
    /**
     * @phpstan-var array<string, string>
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'merchant_order_reference' => SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
        'marketplace_order_reference' => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        'shipment_id' => SpySalesShipmentTableMap::COL_ID_SALES_SHIPMENT,
        'canceled_amount' => SpySalesExpenseTableMap::COL_CANCELED_AMOUNT,
        'discount_amount_aggregation' => SpySalesExpenseTableMap::COL_DISCOUNT_AMOUNT_AGGREGATION,
        'gross_price' => SpySalesExpenseTableMap::COL_GROSS_PRICE,
        'name' => SpySalesExpenseTableMap::COL_NAME,
        'net_price' => SpySalesExpenseTableMap::COL_NET_PRICE,
        'price' => SpySalesExpenseTableMap::COL_PRICE,
        'price_to_pay_aggregation' => SpySalesExpenseTableMap::COL_PRICE_TO_PAY_AGGREGATION,
        'refundable_amount' => SpySalesExpenseTableMap::COL_REFUNDABLE_AMOUNT,
        'tax_amount' => SpySalesExpenseTableMap::COL_TAX_AMOUNT,
        'tax_amount_after_cancellation' => SpySalesExpenseTableMap::COL_TAX_AMOUNT_AFTER_CANCELLATION,
        'tax_rate' => SpySalesExpenseTableMap::COL_TAX_RATE,
        'type' => SpySalesExpenseTableMap::COL_TYPE,
        'expense_created_at' => SpySalesExpenseTableMap::COL_CREATED_AT,
        'expense_updated_at' => SpySalesExpenseTableMap::COL_UPDATED_AT,
        'merchant_order_store' => SpySalesOrderTableMap::COL_STORE,
        'merchant_name' => SpyMerchantTableMap::COL_NAME,
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

        $mappedMerchantSalesExpenses = [];
        foreach ($merchantSalesExpenseRows as $merchantSalesExpenseRow) {
            foreach ($fields as $coloumn => $field) {
                $mappedMerchantSalesExpenseRow[$field] = $merchantSalesExpenseRow[$coloumn] ?? null;
            }
            $mappedMerchantSalesExpenses[] = $mappedMerchantSalesExpenseRow;
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
