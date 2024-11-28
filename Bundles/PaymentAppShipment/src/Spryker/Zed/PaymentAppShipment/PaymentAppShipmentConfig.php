<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentAppShipmentConfig extends AbstractBundleConfig
{
    /**
     * @var array<string, string>
     */
    protected const EXPRESS_CHECKOUT_SHIPMENT_METHODS_INDEXED_BY_PAYMENT_METHOD = [];

    /**
     * @var list<string>
     */
    protected const SHIPMENT_ITEM_COLLECTION_FIELD_NAMES = [];

    /**
     * Specification:
     * - Retrieves the shipment method keys indexed by payment method key.
     * - Example: ['payone-paypal-express' => 'spryker_dummy_shipment-standard'].
     * - The key is the payment method key and the value is the shipment method key.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getExpressCheckoutShipmentMethodsIndexedByPaymentMethod(): array
    {
        return static::EXPRESS_CHECKOUT_SHIPMENT_METHODS_INDEXED_BY_PAYMENT_METHOD;
    }

    /**
     * Specification:
     * - Retrieves the `QuoteTransfer` field names containing `ItemTransfer` collection to iterate over for setting the shipment.
     * - Can be used to set shipment to the items in the cart from the different domains.
     *
     * @api
     *
     * @return list<string>
     */
    public function getShipmentItemCollectionFieldNames(): array
    {
        return static::SHIPMENT_ITEM_COLLECTION_FIELD_NAMES;
    }
}
