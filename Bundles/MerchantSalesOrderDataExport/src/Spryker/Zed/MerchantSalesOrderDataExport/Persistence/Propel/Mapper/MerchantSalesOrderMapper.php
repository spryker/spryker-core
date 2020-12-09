<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

class MerchantSalesOrderMapper
{
    public const KEY_MERCHANT_ORDER_COMMENTS = 'merchant_order_comments';

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderCommentMapper
     */
    protected $merchantSalesOrderCommentMapper;

    /**
     * @phpstan-var array<string, string>
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'merchant_order_reference' => 'SpyMerchantSalesOrder.MerchantSalesOrderReference',
        'marketplace_order_reference' => 'SpySalesOrder.OrderReference',
        'customer_reference' => 'SpySalesOrder.CustomerReference',
        'merchant_order_created_at' => 'SpyMerchantSalesOrder.CreatedAt',
        'merchant_order_updated_at' => 'SpyMerchantSalesOrder.UpdatedAt',
        'merchant_order_store' => 'SpySalesOrder.Store',
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
        'merchant_order_totals_canceled_total' => 'MerchantOrderTotal.CanceledTotal',
        'merchant_order_totals_discount_total' => 'MerchantOrderTotal.DiscountTotal',
        'merchant_order_totals_grand_total' => 'MerchantOrderTotal.GrandTotal',
        'merchant_order_totals_order_expense_total' => 'MerchantOrderTotal.OrderExpenseTotal',
        'merchant_order_totals_refund_total' => 'MerchantOrderTotal.RefundTotal',
        'merchant_order_totals_subtotal' => 'MerchantOrderTotal.Subtotal',
        'merchant_order_totals_tax_total' => 'MerchantOrderTotal.TaxTotal',
    ];

    /**
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderCommentMapper $merchantSalesOrderCommentMapper
     */
    public function __construct(MerchantSalesOrderCommentMapper $merchantSalesOrderCommentMapper)
    {
        $this->merchantSalesOrderCommentMapper = $merchantSalesOrderCommentMapper;
    }

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
     * @param array $merchantSalesOrderRows
     * @param string[] $fields
     *
     * @return array
     */
    public function mapMerchantSalesOrderDataByField(array $merchantSalesOrderRows, array $fields): array
    {
        $selectedFields = array_values(array_intersect_key($fields, $merchantSalesOrderRows[0] ?? []));

        $mappedMerchantSalesOrders = [];
        foreach ($merchantSalesOrderRows as $merchantSalesOrderRow) {
            $mappedMerchantSalesOrderRow = array_combine($selectedFields, $merchantSalesOrderRow);

            if (isset($mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS])) {
                $mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS] = $this->merchantSalesOrderCommentMapper
                    ->mapMerchantSalesOrderCommentTransfersToJson($mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS]);
            }

            $mappedMerchantSalesOrders[] = $mappedMerchantSalesOrderRow;
        }

        return $mappedMerchantSalesOrders;
    }
}
