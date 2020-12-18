<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderAddressTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;

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
        'merchant_order_reference' => SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
        'marketplace_order_reference' => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        'customer_reference' => SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE,
        'merchant_order_created_at' => SpyMerchantSalesOrderTableMap::COL_CREATED_AT,
        'merchant_order_updated_at' => SpyMerchantSalesOrderTableMap::COL_UPDATED_AT,
        'merchant_order_store' => SpySalesOrderTableMap::COL_STORE,
        'email' => SpySalesOrderTableMap::COL_EMAIL,
        'salutation' => SpySalesOrderTableMap::COL_SALUTATION,
        'first_name' => SpySalesOrderTableMap::COL_FIRST_NAME,
        'last_name' => SpySalesOrderTableMap::COL_LAST_NAME,
        'order_note' => SpySalesOrderTableMap::COL_CART_NOTE,
        'currency_iso_code' => SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE,
        'price_mode' => SpySalesOrderTableMap::COL_PRICE_MODE,
        'locale_name' => SpyLocaleTableMap::COL_LOCALE_NAME,
        'billing_address_salutation' => SpySalesOrderAddressTableMap::COL_SALUTATION,
        'billing_address_first_name' => SpySalesOrderAddressTableMap::COL_FIRST_NAME,
        'billing_address_last_name' => SpySalesOrderAddressTableMap::COL_LAST_NAME,
        'billing_address_middle_name' => SpySalesOrderAddressTableMap::COL_MIDDLE_NAME,
        'billing_address_email' => SpySalesOrderAddressTableMap::COL_EMAIL,
        'billing_address_cell_phone' => SpySalesOrderAddressTableMap::COL_CELL_PHONE,
        'billing_address_phone' => SpySalesOrderAddressTableMap::COL_PHONE,
        'billing_address_address1' => SpySalesOrderAddressTableMap::COL_ADDRESS1,
        'billing_address_address2' => SpySalesOrderAddressTableMap::COL_ADDRESS2,
        'billing_address_address3' => SpySalesOrderAddressTableMap::COL_ADDRESS3,
        'billing_address_city' => SpySalesOrderAddressTableMap::COL_CITY,
        'billing_address_zip_code' => SpySalesOrderAddressTableMap::COL_ZIP_CODE,
        'billing_address_po_box' => SpySalesOrderAddressTableMap::COL_PO_BOX,
        'billing_address_company' => SpySalesOrderAddressTableMap::COL_COMPANY,
        'billing_address_description' => SpySalesOrderAddressTableMap::COL_DESCRIPTION,
        'billing_address_comment' => SpySalesOrderAddressTableMap::COL_COMMENT,
        'billing_address_country' => SpyCountryTableMap::COL_NAME,
        'billing_address_region' => SpyRegionTableMap::COL_NAME,
        'merchant_order_totals_canceled_total' => SpyMerchantSalesOrderTotalsTableMap::COL_CANCELED_TOTAL,
        'merchant_order_totals_discount_total' => SpyMerchantSalesOrderTotalsTableMap::COL_DISCOUNT_TOTAL,
        'merchant_order_totals_grand_total' => SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL,
        'merchant_order_totals_order_expense_total' => SpyMerchantSalesOrderTotalsTableMap::COL_ORDER_EXPENSE_TOTAL,
        'merchant_order_totals_refund_total' => SpyMerchantSalesOrderTotalsTableMap::COL_REFUND_TOTAL,
        'merchant_order_totals_subtotal' => SpyMerchantSalesOrderTotalsTableMap::COL_SUBTOTAL,
        'merchant_order_totals_tax_total' => SpyMerchantSalesOrderTotalsTableMap::COL_TAX_TOTAL,
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

            if (($mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS])) {
                $mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS] = $this->merchantSalesOrderCommentMapper
                    ->mapMerchantSalesOrderCommentTransfersToJson($mappedMerchantSalesOrderRow[static::KEY_MERCHANT_ORDER_COMMENTS]);
            }

            $mappedMerchantSalesOrders[] = $mappedMerchantSalesOrderRow;
        }

        return $mappedMerchantSalesOrders;
    }
}
