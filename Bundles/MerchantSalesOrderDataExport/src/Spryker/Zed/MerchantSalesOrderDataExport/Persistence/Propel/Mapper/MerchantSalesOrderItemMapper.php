<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

class MerchantSalesOrderItemMapper
{
    /**
     * @phpstan-var array<string, string>
     *
     * @module SalesOms
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'merchant_order_reference' => 'SpyMerchantSalesOrder.MerchantSalesOrderReference',
        'marketplace_order_reference' => 'SpySalesOrder.OrderReference',
        'product_name' => 'SpySalesOrderItem.Name',
        'merchant_order_item_reference' => 'SpyMerchantSalesOrderItem.MerchantOrderItemReference',
        'product_sku' => 'SpySalesOrderItem.Sku',
        'canceled_amount' => 'SpySalesOrderItem.CanceledAmount',
        'order_item_note' => 'SpySalesOrderItem.CartNote',
        'discount_amount_aggregation' => 'SpySalesOrderItem.DiscountAmountAggregation',
        'discount_amount_full_aggregation' => 'SpySalesOrderItem.DiscountAmountFullAggregation',
        'expense_price_aggregation' => 'SpySalesOrderItem.ExpensePriceAggregation',
        'gross_price' => 'SpySalesOrderItem.GrossPrice',
        'net_price' => 'SpySalesOrderItem.NetPrice',
        'price' => 'SpySalesOrderItem.Price',
        'price_to_pay_aggregation' => 'SpySalesOrderItem.PriceToPayAggregation',
        'product_option_price_aggregation' => 'SpySalesOrderItem.ProductOptionPriceAggregation',
        'quantity' => 'SpySalesOrderItem.Quantity',
        'refundable_amount' => 'SpySalesOrderItem.RefundableAmount',
        'subtotal_aggregation' => 'SpySalesOrderItem.SubtotalAggregation',
        'tax_amount' => 'SpySalesOrderItem.TaxAmount',
        'tax_amount_after_cancellation' => 'SpySalesOrderItem.TaxAmountAfterCancellation',
        'tax_amount_full_aggregation' => 'SpySalesOrderItem.TaxAmountFullAggregation',
        'tax_rate' => 'SpySalesOrderItem.TaxRate',
        'tax_rate_average_aggregation' => 'SpySalesOrderItem.TaxRateAverageAggregation',
        'created_at' => 'SpyMerchantSalesOrderItem.CreatedAt',
        'updated_at' => 'SpyMerchantSalesOrderItem.UpdatedAt',
        'merchant_order_item_state' => 'State.Name',
        'merchant_order_item_state_description' => 'State.Description',
        'merchant_order_item_process' => 'Process.Name',
        'merchant_order_item_bundle_id' => 'SpySalesOrderItemBundle.IdSalesOrderItemBundle',
        'merchant_order_item_bundle_note' => 'SpySalesOrderItemBundle.CartNote',
        'merchant_order_item_bundle_gross_price' => 'SpySalesOrderItemBundle.GrossPrice',
        'merchant_order_item_bundle_image' => 'SpySalesOrderItemBundle.Image',
        'merchant_order_item_bundle_product_name' => 'SpySalesOrderItemBundle.Name',
        'merchant_order_item_bundle_net_price' => 'SpySalesOrderItemBundle.NetPrice',
        'merchant_order_item_bundle_price' => 'SpySalesOrderItemBundle.Price',
        'merchant_order_item_bundle_product_sku' => 'SpySalesOrderItemBundle.Sku',
        'order_shipment_id' => 'SpySalesShipment.IdSalesShipment',
        'shipment_carrier_name' => 'SpySalesShipment.CarrierName',
        'shipment_delivery_time' => 'SpySalesShipment.DeliveryTime',
        'shipment_method_name' => 'SpySalesShipment.Name',
        'shipment_requested_delivery_date' => 'SpySalesShipment.RequestedDeliveryDate',
        'shipping_address_salutation' => 'SpySalesOrderAddress.Salutation',
        'shipping_address_first_name' => 'SpySalesOrderAddress.FirstName',
        'shipping_address_last_name' => 'SpySalesOrderAddress.LastName',
        'shipping_address_middle_name' => 'SpySalesOrderAddress.MiddleName',
        'shipping_address_email' => 'SpySalesOrderAddress.Email',
        'shipping_address_cell_phone' => 'SpySalesOrderAddress.CellPhone',
        'shipping_address_phone' => 'SpySalesOrderAddress.Phone',
        'shipping_address_address1' => 'SpySalesOrderAddress.Address1',
        'shipping_address_address2' => 'SpySalesOrderAddress.Address2',
        'shipping_address_address3' => 'SpySalesOrderAddress.Address3',
        'shipping_address_city' => 'SpySalesOrderAddress.City',
        'shipping_address_zip_code' => 'SpySalesOrderAddress.ZipCode',
        'shipping_address_po_box' => 'SpySalesOrderAddress.PoBox',
        'shipping_address_company' => 'SpySalesOrderAddress.Company',
        'shipping_address_description' => 'SpySalesOrderAddress.Description',
        'shipping_address_comment' => 'SpySalesOrderAddress.Comment',
        'shipping_address_country' => 'Country.Name',
        'shipping_address_region' => 'Region.Name',
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
     * @param array $merchantSalesOrderItemRows
     *
     * @return array
     */
    public function mapMerchantSalesOrderItemDataByField(array $merchantSalesOrderItemRows): array
    {
        $fields = $this->getFields();
        $selectedFields = array_values(array_intersect_key($fields, $merchantSalesOrderItemRows[0] ?? []));

        $mappedMerchantSalesOrderItems = [];
        foreach ($merchantSalesOrderItemRows as $merchantSalesOrderItemRow) {
            $mappedMerchantSalesOrderItems[] = array_combine($selectedFields, $merchantSalesOrderItemRow);
        }

        return $mappedMerchantSalesOrderItems;
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
