<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Braintree\PageObject;

class CheckoutPage
{
    const URL = '/checkout';
    const URL_CUSTOMER = '/checkout/customer';
    const URL_ADDRESS = '/checkout/address';
    const URL_SHIPMENT = '/checkout/shipment';
    const URL_PAYMENT = '/checkout/payment';
    const URL_SUMMARY = '/checkout/summary';
    const URL_SUCCESS = '/checkout/success';

    const BUTTON_CHECKOUT = 'Checkout';
    const BUTTON_GO_TO_PAYMENT = 'Go to Payment';

    const SHIPMENT_SELECTION = 'shipmentForm_idShipmentMethod_0';
}
