<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper;

class SalesOrderItemMapper
{
    /**
     * @phpstan-var array<string, string>
     *
     * @module SalesOms
     *
     * @var string[]
     */
    protected $fieldMapping = [
        'order_reference' => 'Order.OrderReference',
        'order_item_reference' => 'SpySalesOrderItem.OrderItemReference',
        'product_name' => 'SpySalesOrderItem.Name',
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
        'created_at' => 'SpySalesOrderItem.CreatedAt',
        'uploaded_at' => 'SpySalesOrderItem.UpdatedAt',
        'order_item_state' => 'State.Name',
        'order_item_state_description' => 'State.Description',
        'order_item_process' => 'Process.Name',
        'order_item_bundle_id' => 'SalesOrderItemBundle.IdSalesOrderItemBundle',
        'order_item_bundle_note' => 'SalesOrderItemBundle.CartNote',
        'order_item_bundle_gross_price' => 'SalesOrderItemBundle.GrossPrice',
        'order_item_bundle_image' => 'SalesOrderItemBundle.Image',
        'order_item_bundle_product_name' => 'SalesOrderItemBundle.Name',
        'order_item_bundle_net_price' => 'SalesOrderItemBundle.NetPrice',
        'order_item_bundle_price' => 'SalesOrderItemBundle.Price',
        'order_item_bundle_product_sku' => 'SalesOrderItemBundle.Sku',
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
     * @param array $salesOrderItemRows
     *
     * @return array
     */
    public function mapSalesOrderItemDataByField(array $salesOrderItemRows): array
    {
        $fields = $this->getFields();
        $selectedFields = array_values(array_intersect_key($fields, $salesOrderItemRows[0] ?? []));

        $mappedSalesOrderItems = [];
        foreach ($salesOrderItemRows as $salesOrderItemRow) {
            $mappedSalesOrderItems[] = array_combine($selectedFields, $salesOrderItemRow);
        }

        return $mappedSalesOrderItems;
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
