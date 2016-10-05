/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $ = require('jquery');
var paymentMethod = require('./payment-method');

$(document).ready(function() {
    if (window.braintree == null) {
        window.braintree = true;
        paymentMethod.init({
            formSelector: '#payment-form',
            paymentMethodSelector: '#paymentForm_paymentSelection input[type="radio"]',
            currentPaymentMethodSelector: '#paymentForm_paymentSelection input[type="radio"]:checked',
            nonceInputName: 'payment_method_nonce'
        });
    }
});
