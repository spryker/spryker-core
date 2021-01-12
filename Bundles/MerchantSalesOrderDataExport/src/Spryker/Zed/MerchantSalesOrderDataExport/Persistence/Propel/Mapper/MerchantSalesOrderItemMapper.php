<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\ProductBundle\Persistence\Map\SpySalesOrderItemBundleTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderAddressTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineProcessTableMap;

class MerchantSalesOrderItemMapper
{
    protected const KEY_SHIPPING_ADDRESS_SALUTATION = 'shipping_address_salutation';

    /**
     * @phpstan-var array<string, string>
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'merchant_order_reference' => SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
        'marketplace_order_reference' => SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        'product_name' => SpySalesOrderItemTableMap::COL_NAME,
        'merchant_order_item_reference' => SpyMerchantSalesOrderItemTableMap::COL_MERCHANT_ORDER_ITEM_REFERENCE,
        'product_sku' => SpySalesOrderItemTableMap::COL_SKU,
        'canceled_amount' => SpySalesOrderItemTableMap::COL_CANCELED_AMOUNT,
        'order_item_note' => SpySalesOrderItemTableMap::COL_CART_NOTE,
        'discount_amount_aggregation' => SpySalesOrderItemTableMap::COL_DISCOUNT_AMOUNT_AGGREGATION,
        'discount_amount_full_aggregation' => SpySalesOrderItemTableMap::COL_DISCOUNT_AMOUNT_FULL_AGGREGATION,
        'expense_price_aggregation' => SpySalesOrderItemTableMap::COL_EXPENSE_PRICE_AGGREGATION,
        'gross_price' => SpySalesOrderItemTableMap::COL_GROSS_PRICE,
        'net_price' => SpySalesOrderItemTableMap::COL_NET_PRICE,
        'price' => SpySalesOrderItemTableMap::COL_PRICE,
        'price_to_pay_aggregation' => SpySalesOrderItemTableMap::COL_PRICE_TO_PAY_AGGREGATION,
        'product_option_price_aggregation' => SpySalesOrderItemTableMap::COL_PRODUCT_OPTION_PRICE_AGGREGATION,
        'quantity' => SpySalesOrderItemTableMap::COL_QUANTITY,
        'refundable_amount' => SpySalesOrderItemTableMap::COL_REFUNDABLE_AMOUNT,
        'subtotal_aggregation' => SpySalesOrderItemTableMap::COL_SUBTOTAL_AGGREGATION,
        'tax_amount' => SpySalesOrderItemTableMap::COL_TAX_AMOUNT,
        'tax_amount_after_cancellation' => SpySalesOrderItemTableMap::COL_TAX_AMOUNT_AFTER_CANCELLATION,
        'tax_amount_full_aggregation' => SpySalesOrderItemTableMap::COL_TAX_AMOUNT_FULL_AGGREGATION,
        'tax_rate' => SpySalesOrderItemTableMap::COL_TAX_RATE,
        'tax_rate_average_aggregation' => SpySalesOrderItemTableMap::COL_TAX_RATE_AVERAGE_AGGREGATION,
        'merchant_order_item_created_at' => SpyMerchantSalesOrderItemTableMap::COL_CREATED_AT,
        'merchant_order_item_updated_at' => SpyMerchantSalesOrderItemTableMap::COL_UPDATED_AT,
        'merchant_order_item_state' => SpyStateMachineItemStateTableMap::COL_NAME,
        'merchant_order_item_state_description' => SpyStateMachineItemStateTableMap::COL_DESCRIPTION,
        'merchant_order_item_process' => SpyStateMachineProcessTableMap::COL_NAME,
        'merchant_order_item_bundle_id' => SpySalesOrderItemBundleTableMap::COL_ID_SALES_ORDER_ITEM_BUNDLE,
        'merchant_order_item_bundle_note' => SpySalesOrderItemBundleTableMap::COL_CART_NOTE,
        'merchant_order_item_bundle_gross_price' => SpySalesOrderItemBundleTableMap::COL_GROSS_PRICE,
        'merchant_order_item_bundle_image' => SpySalesOrderItemBundleTableMap::COL_IMAGE,
        'merchant_order_item_bundle_product_name' => SpySalesOrderItemBundleTableMap::COL_NAME,
        'merchant_order_item_bundle_net_price' => SpySalesOrderItemBundleTableMap::COL_NET_PRICE,
        'merchant_order_item_bundle_price' => SpySalesOrderItemBundleTableMap::COL_PRICE,
        'merchant_order_item_bundle_product_sku' => SpySalesOrderItemBundleTableMap::COL_SKU,
        'order_shipment_id' => SpySalesShipmentTableMap::COL_ID_SALES_SHIPMENT,
        'shipment_carrier_name' => SpySalesShipmentTableMap::COL_CARRIER_NAME,
        'shipment_delivery_time' => SpySalesShipmentTableMap::COL_DELIVERY_TIME,
        'shipment_method_name' => SpySalesShipmentTableMap::COL_NAME,
        'shipment_requested_delivery_date' => SpySalesShipmentTableMap::COL_REQUESTED_DELIVERY_DATE,
        'shipping_address_salutation' => SpySalesOrderAddressTableMap::COL_SALUTATION,
        'shipping_address_first_name' => SpySalesOrderAddressTableMap::COL_FIRST_NAME,
        'shipping_address_last_name' => SpySalesOrderAddressTableMap::COL_LAST_NAME,
        'shipping_address_middle_name' => SpySalesOrderAddressTableMap::COL_MIDDLE_NAME,
        'shipping_address_email' => SpySalesOrderAddressTableMap::COL_EMAIL,
        'shipping_address_cell_phone' => SpySalesOrderAddressTableMap::COL_CELL_PHONE,
        'shipping_address_phone' => SpySalesOrderAddressTableMap::COL_PHONE,
        'shipping_address_address1' => SpySalesOrderAddressTableMap::COL_ADDRESS1,
        'shipping_address_address2' => SpySalesOrderAddressTableMap::COL_ADDRESS2,
        'shipping_address_address3' => SpySalesOrderAddressTableMap::COL_ADDRESS3,
        'shipping_address_city' => SpySalesOrderAddressTableMap::COL_CITY,
        'shipping_address_zip_code' => SpySalesOrderAddressTableMap::COL_ZIP_CODE,
        'shipping_address_po_box' => SpySalesOrderAddressTableMap::COL_PO_BOX,
        'shipping_address_company' => SpySalesOrderAddressTableMap::COL_COMPANY,
        'shipping_address_description' => SpySalesOrderAddressTableMap::COL_DESCRIPTION,
        'shipping_address_comment' => SpySalesOrderAddressTableMap::COL_COMMENT,
        'shipping_address_country' => SpyCountryTableMap::COL_NAME,
        'shipping_address_region' => SpyRegionTableMap::COL_NAME,
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
     * @param mixed[][] $merchantSalesOrderItemRows
     *
     * @return mixed[][]
     */
    public function mapMerchantSalesOrderItemDataByField(array $merchantSalesOrderItemRows): array
    {
        $mappedMerchantSalesOrderItems = [];
        foreach ($merchantSalesOrderItemRows as $merchantSalesOrderItemRow) {
            $mappedMerchantSalesOrderItemRow = [];
            foreach ($this->fieldMapping as $field => $column) {
                $mappedMerchantSalesOrderItemRow[$field] = $merchantSalesOrderItemRow[$column] ?? null;
            }
            if ($merchantSalesOrderItemRow[SpySalesOrderAddressTableMap::COL_SALUTATION] !== null) {
                $shippingAddressSalutationValueSet = SpySalesOrderAddressTableMap::getValueSet(SpySalesOrderAddressTableMap::COL_SALUTATION);
                $mappedMerchantSalesOrderItemRow[static::KEY_SHIPPING_ADDRESS_SALUTATION] = $shippingAddressSalutationValueSet[$merchantSalesOrderItemRow[SpySalesOrderAddressTableMap::COL_SALUTATION]];
            }
            $mappedMerchantSalesOrderItems[] = $mappedMerchantSalesOrderItemRow;
        }

        return $mappedMerchantSalesOrderItems;
    }
}
